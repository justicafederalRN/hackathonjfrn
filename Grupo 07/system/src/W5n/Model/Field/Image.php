<?php
namespace W5n\Model\Field;

class Image extends Field implements Uploadable
{

    protected static $prefixPath = '';
    protected static $prefixUri  = '';

    const KEEP_ASPECT = 'K';
    const RESIZE      = 'R';
    const CROP       = 'C';


    protected $otherErrorsMessage;
    protected $noImageMessage;
    protected $unsupportedTypeMessage;
    protected $unknownErrorMessage;
    protected $partialUploadMessage;
    protected $notUploadedFileMessage;
    protected $noTmpDirMessage;
    protected $noFileMessage;
    protected $iniSizeMessage;
    protected $formSizeMessage;
    protected $uploadExtMessage;
    protected $cantWriteMessage;
    protected $cantCreateDirMessage;

    protected $toProcess = array();


    const IMG_ERR_OK         = 0;
    const IMG_ERR_INI_SIZE   = 1;
    const IMG_ERR_FORM_SIZE  = 2;
    const IMG_ERR_PARTIAL    = 3;
    const IMG_ERR_NO_FILE    = 4;
    const IMG_ERR_NO_TMP_DIR = 6;
    const IMG_ERR_CANT_WRITE = 7;
    const IMG_ERR_EXTENSION  = 8;
    const IMG_ERR_NO_IMAGE   = 9;

    const IMG_ERR_NO_UPLOADED_FILE = 10;
    const IMG_ERR_UNSUPPORTED_TYPE = 11;
    const IMG_ERR_CANT_CREATE_DIR  = 12;
    const IMG_ERR_OTHER_ERRORS     = 13;
    const IMG_ERR_UNKNOWN          = 14;

    public function __construct(
        $name, $label,
        $w = null, $h = null,
        $mode = 'RKC',
        $dir  = null,
        $quality = 100,
        $imageName = null,
        $createDir = true
    ) {
        parent::__construct($name, $label);

        $this->registerImage(
            $name, $w, $h, $mode, $dir, $quality, $imageName, $createDir
        );
    }

    protected function registerImage(
        $name,
        $w = null, $h = null,
        $mode = 'RKC',
        $dir  = null,
        $quality = 80,
        $imageName = null,
        $createDir = true,
        $prefix = ''
    ) {
        $keepAspect = $resize = $crop = false;
        if (!empty($mode)) {
            $mode = strtoupper($mode);
            $keepAspect = stripos($mode, self::KEEP_ASPECT) !== false;
            $resize     = stripos($mode, self::RESIZE) !== false;
            $crop       = stripos($mode, self::CROP) !== false;
        }

        $options = array(
            'width'      => $w,
            'height'     => $h,
            'aspect'     => $keepAspect,
            'resize'     => $resize,
            'crop'       => $crop,
            'dir'        => $dir,
            'quality'    => $quality,
            'name'       => $imageName,
            'createDir'  => $createDir,
            'prefix'     => $prefix
        );
        $this->setOptions($options);
        $this->toProcess[$name] = $options;
    }

    public function addThumb(
        $prefix,
        $w = null, $h = null,
        $mode = 'RKC',
        $dir  = null,
        $quality = 80,
        $imageName = null,
        $createDir = true
    ) {
        $this->registerImage(
            $this->getName() . $prefix,
            $w, $h, $mode, $dir, $quality, $imageName, $createDir, $prefix
        );
        return $this;
    }

    public static function getPrefixPath()
    {
        return self::$prefixPath;
    }

    public static function getPrefixUri()
    {
        return self::$prefixUri;
    }

    public static function setPrefixPath($prefixPath)
    {
        self::$prefixPath = rtrim($prefixPath, '\\/') . DIRECTORY_SEPARATOR;
    }

    public static function setPrefixUri($prefixUri)
    {
        self::$prefixUri = rtrim($prefixUri, '/') . '/';
    }


    protected function loadDefaultMessages()
    {
        $this->setCantCreateDirMessage('O diretório não pode ser criado no servidor. Contate o administrador');
        $this->setCantWriteMessage('O arquivo não pode ser escrito no servidor. Contate o adminsitrador');
        $this->setFormSizeMessage('O tamanho do arquivo é maior que o permitido.');
        $this->setIniSizeMessage('O tamanho do arquivo é maior que o permitido.');
        $this->setNoFileMessage('O arquivo deve ser informado.');
        $this->setNoImageMessage('O arquivo não é uma imagem');
        $this->setNoTmpDirMessage('Não existe diretório temporário para a transferencia do arquivo. Contate o administrador.');
        $this->setNotUploadedFileMessage('A imagem não foi enviada ao servidor na forma correta. Tente novamente.');
        $this->setOtherErrorsMessage('O arquivo não foi processado pois o formulário possui outros erros. Envie o arquivo novamente');
        $this->setPartialUploadMessage('O arquivo foi enviado parcialmente. Tente novamente.');
        $this->setUnknownErrorMessage('Ocorreu um erro interno do servidor. Tente novamente. Caso o problema persista contate o administrador.');
        $this->setUnsupportedTypeMessage('O tipo de imagem enviado não é suportado pelo servidor.');
        $this->setUploadExtMessage('O arquivo não possui uma extensão válida');
        $this->setMaxSizeMessage('Arquivo maior que o tamanho permitido.');
    }


    public function beforeSave($operation)
    {
        $removeFieldName = $this->getName().'_delete';
        $model           = $this->getModel();
        if (isset($model->{$removeFieldName}) && $model->{$removeFieldName}) {
            $this->deleteImages($model);
            $this->value = null;
        } else {
            $this->processImages($this->getModel(), $operation == \W5n\Model\Model::OP_INSERT);
        }
    }

    protected function processImages($model, $insert = true)
    {
        $data = $this->toProcess;
        if (!isset($this->toProcess[$this->getName()])) {
            return;
        }
        $mainImage = $this->toProcess[$this->getName()];

        $result = $this->createImage(
            $mainImage, false, $insert, $model
        );
        if (!$result) {
            return;
        }

        foreach ($this->toProcess as $fieldName => $data) {
            if ($fieldName == $this->getName())
                continue;

            $this->createImage(
                $data, true, $insert, $model
            );
        }
    }

    protected function createImage($data, $isThumb = false, $isInsert = true, $model = null)
    {
        if ($this->hasError()) {
            return false;
        }
        $fieldName = $this->getName();
        //valida a imagem
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] == self::IMG_ERR_NO_FILE) {
            return true;
        }

        //se tem erros e tem mensagem de erro não processa
        if ($model->hasErrors()) {
            $message = $this->getOtherErrorsMessage();
            if ($message !== false) {
                $this->setErrorByCode(self::IMG_ERR_OTHER_ERRORS);
                return false;
            }
        }


        $width   = $data['width'];
        $height  = $data['height'];
        $crop    = $data['crop'];
        $resize  = $data['resize'];
        $quality = $data['quality'];
        $create  = $data['createDir'];
        $name    = $data['name'];
        $aspect  = $data['aspect'];


        $imgInFiles = $_FILES[$fieldName];

        $errorCode = $imgInFiles['error'];
        if ($errorCode != self::IMG_ERR_OK) {
            $this->setErrorByCode($errorCode);
            return false;
        }

        if (!is_uploaded_file($imgInFiles['tmp_name'])) {
            $this->setErrorByCode(self::IMG_ERR_NO_UPLOADED_FILE);
            return false;
        }

        $dir = self::getPrefixPath() .  $data['dir'];

        if (!is_dir($dir) && $create) {
            if (!@mkdir($dir, 0766, true)) {
                $this->setErrorByCode(self::IMG_ERR_CANT_CREATE_DIR);
                return false;
            }
        }
        $imgData = getimagesize($imgInFiles['tmp_name']);
        if ($imgData === false) {
            $this->setErrorByCode(self::IMG_ERR_NO_IMAGE);
            return false;
        }

        $sWidth  = $imgData[0];
        $sHeight = $imgData[1];
        $sType   = $imgData[2];
        $sExt    = strtolower(preg_replace('#.*\.#', '', $imgInFiles['name']));



        //if empty width and height only upload file
        if (empty($width) && empty($height)) {
            $width = $sWidth;
            $height = $sHeight;
        } else if (empty($width) XOR empty($height)) {
            if (empty($width)) {
                $width = ceil($this->ruleOfThree($sHeight, $height, $sWidth));
            } else {
                $height = ceil($this->ruleOfThree($sWidth, $width, $sHeight));
            }
        }


        //processing image
        $imgRsc = null;
        switch ($sType) {
            case IMAGETYPE_JPEG:
                $imgRsc = \imagecreatefromjpeg($imgInFiles['tmp_name']);
                break;
            case IMAGETYPE_GIF:
                $imgRsc = \imagecreatefromgif($imgInFiles['tmp_name']);
                break;
            case IMAGETYPE_PNG:
                $imgRsc = \imagecreatefrompng($imgInFiles['tmp_name']);
                \imagesavealpha($imgRsc, true);
                break;
            default:
                $this->setErrorByCode(self::IMG_ERR_UNSUPPORTED_TYPE);
                return false;
        }

        $nWidth = $width;
        $nHeight = $height;
        if (empty($nWidth)) {
            $hRatio = $nHeight / $sHeight;
            $nWidth = \round($sWidth * $hRatio);
        }

        if (empty($nHeight)) {
            $wRatio = $nWidth / $sWidth;
            $nHeight = \round($sHeight * $wRatio);
        }

        $nImgRsc = null;
        //resise aspect
        if ($aspect) {
            $wRatio = $nWidth / $sWidth;
            $_nHeight = \round($sHeight * $wRatio);
            if ($_nHeight > $nHeight) {
                $hRatio = $nHeight / $sHeight;
                $nWidth = \round($sWidth * $hRatio);
            } else {
                $nHeight = $_nHeight;
            }
        }

        $nImgRsc = \imagecreatetruecolor($nWidth, $nHeight);
        \imagealphablending($nImgRsc, false);
        $tmpWidth = $resize ? $sWidth : $nWidth;
        $tmpHeight = $resize ? $sHeight : $nHeight;
        $tmpX = 0;
        $tmpY = 0;

        if ($crop && !$aspect) {

            if (!$resize) {
                $tmpWidth = $width;
                $tmpHeight = $height;
            } else {
                if ($width > $height) {
                    $tmpHeight = \ceil($this->ruleOfThree($width, $sWidth, $height));
                    $tmpWidth  = $sWidth;
                    if ($tmpHeight > $sHeight) {
                        $tmpHeight = $sHeight;
                        $tmpWidth  = ceil($this->ruleOfThree($height, $sHeight, $width));
                    }
                } else {
                    $tmpHeight = $sHeight;
                    $tmpWidth  = \ceil($this->ruleOfThree($height, $sHeight, $width));
                    if ($tmpWidth > $sWidth) {
                        $tmpHeight = ceil($this->ruleOfThree($width, $sWidth, $height));
                        $tmpWidth  = $sWidth;
                    }
                }
                $tmpX = ($sWidth - $tmpWidth) / 2;
                $tmpY = ($sHeight - $tmpHeight) / 2;
            }
        }
        \imagecopyresampled($nImgRsc, $imgRsc, 0, 0, $tmpX, $tmpY, $nWidth, $nHeight, $tmpWidth, $tmpHeight);

        $newFileName = '';
        $prefix      = $isThumb ? $data['prefix'] : '';
        //se o campo já possuir um nome

        $fieldValue = $model->{$fieldName};

        if (/*!$isInsert && */!empty($model->{$fieldName})) {
            $curExt = \strtolower(\preg_replace('#.*\.#', '', $model->{$fieldName}));

            //se for a mesma extensão deixa com o mesmo nome
            if ($curExt == $sExt) {
                $newFileName = $model->{$fieldName};
            } else {
                $newFileName = '';
            }
        }

        if (empty($newFileName)) {
            if ($isThumb) {
                $newFileName = $prefix . $this->getValue();
            } else {
                $newFileName = $this->parseFileName($model, $prefix, $name, $sExt, $width, $height, $imgInFiles['size'], $imgInFiles['name']);
            }
        } else {
            $newFileName = $prefix . $newFileName;
        }


        $nFilePath = $dir . DIRECTORY_SEPARATOR . $newFileName;
        $nFileDir  = dirname($nFilePath);
        if (!file_exists($nFileDir)) {
            mkdir($nFileDir, 0766, true);
        }

        $return = false;
        switch ($sType) {
            case IMAGETYPE_JPEG:
                $return = \imagejpeg($nImgRsc, $nFilePath, $quality);
                break;
            case IMAGETYPE_GIF:
                $return = \imagegif($nImgRsc, $nFilePath, $quality);
                break;
            case IMAGETYPE_PNG:
                \imagesavealpha($nImgRsc, true);
                $return = \imagepng($nImgRsc, $nFilePath, (int)min(9, max(1, round(9 * $quality/100))));
                break;
        }

        if (!$return) {
            $this->setErrorByCode(self::IMG_ERR_UNKNOWN);
        } else {
            if (!$isThumb) {
                $this->setValue($newFileName);
            }
        }
        \imagedestroy($nImgRsc);
        \imagedestroy($imgRsc);
        return $return;
    }

    /**
     * x1 = y1
     * x2 = ?
     *
     */
    private function ruleOfThree($x1, $y1, $x2)
    {
        return $x2 * $y1 / $x1;
    }

    function parseFileName($model, $prefix, $name, $ext, $w, $h, $size, $fileName)
    {
        if (empty($name)) {
            $name = '{rand}{rand}/{rand}{rand}/{md5}.{ext}';
        }

        if (preg_match_all('#\{(.*?)\}#is', $name, $matches)) {
            $data = array();
            $data['uniqid'] = uniqid();
            $data['md5'] = md5($data['uniqid']);
            $data['ext'] = $ext;
            $data['width'] = $w;
            $data['height'] = $h;
            $data['size'] = $size;
            $data['name'] = $fileName;
            $data['time'] = time();
            $data['prefix'] = $prefix;
            $data['d'] = date('d', $data['time']);
            $data['m'] = date('m', $data['time']);
            $data['y'] = date('Y', $data['time']);
            $data['h'] = date('H', $data['time']);
            $data['i'] = date('i', $data['time']);
            $data['s'] = date('s', $data['time']);
            $data['ds'] = DIRECTORY_SEPARATOR;
            foreach ($matches[0] as $key => $match) {
                $val = $matches[1][$key];

                if ($val == 'rand') {
                    $name = preg_replace('#' . preg_quote($match, '#') . '#', $this->generateRandChar(), $name, 1);
                    continue;
                }
                if (isset($data[$val]))
                    $name = str_replace($match, $data[$val], $name);
                else if (is_object($model) && isset($model->{$val}))
                    $name = str_replace($match, $model->{$val}, $name);
            }
        }
        return $prefix.$name;
    }

    private function generateRandChar()
    {
        $randGroup = mt_rand(0, 2);
        switch ($randGroup) {
            case 0:
                return chr(mt_rand(48, 57));//0-9
            case 1:
                return chr(mt_rand(65, 90));//A-Z
            default:
                return chr(mt_rand(97, 122));//a-z
        }
    }


    private function setErrorByCode($errorCode)
    {
        $message = false;
        switch ($errorCode) {
            case self::IMG_ERR_CANT_CREATE_DIR:
                $message = $this->getCantCreateDirMessage();
                break;
            case self::IMG_ERR_CANT_WRITE:
                $message = $this->getCantWriteMessage();
                break;
            case self::IMG_ERR_EXTENSION:
                $message = $this->getUploadExtMessage();
                break;
            case self::IMG_ERR_FORM_SIZE:
                $message = $this->getFormSizeMessage();
                break;
            case self::IMG_ERR_INI_SIZE:
                $message = $this->getIniSizeMessage();
                break;
            case self::IMG_ERR_NO_FILE:
                $message = $this->getNoFileMessage();
                break;
            case self::IMG_ERR_NO_IMAGE:
                $message = $this->getNoImageMessage();
                break;
            case self::IMG_ERR_NO_TMP_DIR:
                $message = $this->getNoTmpDirMessage();
                break;
            case self::IMG_ERR_PARTIAL:
                $message = $this->getPartialUploadMessage();
                break;
            case self::IMG_ERR_UNKNOWN:
                $message = $this->getUnknownErrorMessage();
                break;
            case self::IMG_ERR_UNSUPPORTED_TYPE:
                $message = $this->getUnsupportedTypeMessage();
                break;
            case self::IMG_ERR_OTHER_ERRORS:
                $message = $this->getOtherErrorsMessage();
                break;
        }

        if ($message !== false) {
            $this->setError($message);
        }
    }

    function afterDelete($success)
    {
        if ($success && isset($this->toProcess))
            $this->deleteImages($this->getModel());
    }

    protected function deleteImages($model)
    {
        $value = $this->getValue();
        if (empty($value))
            return;

        foreach ($this->toProcess as $p) {
            $this->deleteImage($p);
        }
    }

    protected function deleteImage($data)
    {
        $fullPath = self::getPrefixPath() . $data['dir'] . $data['prefix'] . $this->getValue();
        if (file_exists($fullPath))
            @unlink($fullPath);
    }

    public function setCantCreateDirMessage($cantCreateDirMessage)
    {
        $this->cantCreateDirMessage = $cantCreateDirMessage;
        return $this;
    }

    public function setCantWriteMessage($cantWriteMessage)
    {
        $this->cantWriteMessage = $cantWriteMessage;
        return $this;
    }

    public function setUploadExtMessage($uploadExtMessage)
    {
        $this->uploadExtMessage = $uploadExtMessage;
        return $this;
    }

    public function setFormSizeMessage($formSizeMessage)
    {
        $this->formSizeMessage = $formSizeMessage;
        return $this;
    }

    public function setIniSizeMessage($iniSizeMessage)
    {
        $this->iniSizeMessage = $iniSizeMessage;
        return $this;
    }

    public function setNoFileMessage($noFileMessage)
    {
        $this->noFileMessage = $noFileMessage;
        return $this;
    }

    public function setNoTmpDirMessage($noTmpDirMessage)
    {
        $this->noTmpDirMessage = $noTmpDirMessage;
        return $this;
    }

    public function setNotUploadedFileMessage($notUploadedFileMessage)
    {
        $this->notUploadedFileMessage = $notUploadedFileMessage;
        return $this;
    }

    public function setPartialUploadMessage($partialUploadMessage)
    {
        $this->partialUploadMessage = $partialUploadMessage;
        return $this;
    }

    public function setUnknownErrorMessage($unknownErrorMessage)
    {
        $this->unknownErrorMessage = $unknownErrorMessage;
        return $this;
    }

    public function setUnsupportedTypeMessage($unsupportedTypeMessage)
    {
        $this->unsupportedTypeMessage = $unsupportedTypeMessage;
        return $this;
    }

    public function setNoImageMessage($noImageMessage)
    {
        $this->noImageMessage = $noImageMessage;
        return $this;
    }

    public function setOtherErrorsMessage($otherErrorsMessage)
    {
        $this->otherErrorsMessage = $otherErrorsMessage;
        return $this;
    }

    public function getCantCreateDirMessage()
    {
        return $this->cantCreateDirMessage;
    }

    public function getCantWriteMessage()
    {
        return $this->cantWriteMessage;
    }

    public function getUploadExtMessage()
    {
        return $this->uploadExtMessage;
    }

    public function getFormSizeMessage()
    {
        return $this->formSizeMessage;
    }

    public function getIniSizeMessage()
    {
        return $this->iniSizeMessage;
    }

    public function getNoFileMessage()
    {
        return $this->noFileMessage;
    }

    public function getNoTmpDirMessage()
    {
        return $this->noTmpDirMessage;
    }

    public function getNotUploadedFileMessage()
    {
        return $this->notUploadedFileMessage;
    }

    public function getPartialUploadMessage()
    {
        return $this->partialUploadMessage;
    }

    public function getUnknownErrorMessage()
    {
        return $this->unknownErrorMessage;
    }

    public function getUnsupportedTypeMessage()
    {
        return $this->unsupportedTypeMessage;
    }

    public function getNoImageMessage()
    {
        return $this->noImageMessage;
    }

    public function getOtherErrorsMessage()
    {
        return $this->otherErrorsMessage;
    }

    public function toHtml(Field $field)
    {
        $input = \W5n\Html\HtmlBuilder::input($this->getName(), null, 'file');
        $input->addClass('input-image');
        $this->applyValidationHtmlModifications($input);

        $value = $this->getValue();

        if (!empty($value)) {
            $idx    = count($this->toProcess) > 1 ? 1 : 0;
            $keys   = array_keys($this->toProcess);
            $data   = $this->toProcess[$keys[$idx]];

            $imgUrl = self::getPrefixUri() . $this->ensureDirectorySeparator($data['dir'], '/') . $data['prefix'] . $value;

            $container = \W5n\Html\HtmlBuilder::tag('div');
            $container->addClass('image-displayer row');

            $img = \W5n\Html\HtmlBuilder::img($imgUrl . '?v=' . time());

            $container->appendChild($img);
            $container->appendTag('br')->setEmpty(true);
            $container->appendTag('br')->setEmpty(true);
            if (!$this->isMandatory()) {

                $label = \W5n\Html\HtmlBuilder::tag('label');
                $label->appendTag(
                    'input',
                    array(
                        'type'  => 'checkbox',
                        'name'  => $this->getName() . '_delete',
                        'value' => '1'
                    )
                );
                $label->appendText(' Remover');
                $container->appendTag('div', array('class' => 'checkbox'))->appendChild($label);
            }

            $container->appendChild($input);
            return $container;
        }

        return $input;
    }

    protected function ensureDirectorySeparator($path, $char = DIRECTORY_SEPARATOR)
    {
        return ltrim($path, '\\/') . $char;
    }
}
