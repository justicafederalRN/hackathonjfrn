<?php
namespace W5n\Model\Field;

use W5n\Model\Model;

class Upload extends \W5n\Model\Field\Field implements Uploadable
{

    private $dir;
    private $allowedExts = array();
    private $allowedMimes = array();
    private $maxFileSize = null;
    private $fileName = null;
    private $createDir = null;
    private $sizeField = null;
    private $extField = null;
    private $mimeField = null;

    const UPLOAD_ERR_MAX_SIZE = 5;
    const UPLOAD_ERR_HAS_OTHER_ERRORS = 9;
    const UPLOAD_ERR_CANT_CREATE_DIR = 10;
    const UPLOAD_ERR_NO_UPLOADED_FILE = 11;
    const UPLOAD_ERR_INVALID_EXT = 12;
    const UPLOAD_ERR_INVALID_MIME = 13;
    const UPLOAD_ERR_UNKNOWN_ERROR = 14;


    private $errIniSizeMessage;
    private $errFormSizeMessage;
    private $errMaxSizeMessage;
    private $errPartialUploadMessage;
    private $errNoFileMessage;
    private $errNoTmpDirMessage;
    private $errCantWriteMessage;
    private $errExtensionMessage;
    private $errHasOtherErrorsMessage;
    private $errCantCreateDirMessage;
    private $errNoUploadedFileMessage;
    private $errInvalidExtMessage;
    private $errInvalidMimeMessage;
    private $errUnknownErrorMessage;

    protected static $prefixPath = '';
    protected static $prefixUri  = '';

    function __construct(
        $name, $label, $dir, $fileName = null,
        $allowedExts = array(), $allowedMimes = array(),
        $maxFileSize = null, $sizeField = null, $extField = null, $mimeField = null,
        $createDir = true
    ) {

        parent::__construct($name, $label);

        if (empty(self::$prefixPath)) {
            self::$prefixPath = DOCROOT . 'assets/uploads/';
        }

        if (empty(self::$prefixUri)) {
            self::$prefixUri = \URL::site('/assets/uploads/');
        }

        $this->dir          = $dir;
        $this->fileName     = $fileName;
        $this->allowedExts  = $allowedExts;
        $this->allowedMimes = $allowedMimes;
        $this->maxFileSize  = $maxFileSize;

        $this->createDir = $createDir;
        $this->sizeField = $sizeField;
        $this->mimeField = $mimeField;
        $this->extField  = $extField;

        $this->loadDefaultMessages();
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

    public function beforeSave($operation)
    {
        $isInsert = $operation == Model::OP_INSERT;
        $this->processUpload($this->getModel(), $isInsert);
    }

    protected function processUpload($model, $isInsert = true)
    {
        if (!isset($_FILES[$this->getName()])) {
            if ($isInsert)
                $this->addUploadError($model, $this->getName(), UPLOAD_ERR_NO_FILE);
            return;
        } else if (!$isInsert && $_FILES[$this->getName()]['error'] == UPLOAD_ERR_NO_FILE) {
            return;
        }

        if ($model->hasErrors()) {
            $message = $this->getErrHasOtherErrorsMessage();
            if ($message !== false) {
                $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_HAS_OTHER_ERRORS);
                return;
            }
        }

        $create = $this->createDir;
        $name   = $this->getName();
        $dir    = self::getPrefixPath() . $this->dir;
        $fileNamePattern = $this->fileName;

        $fileInFiles = $_FILES[$this->getName()];
        $errorCode   = $fileInFiles['error'];
        $size        = $fileInFiles['size'];
        $mime        = $fileInFiles['type'];
        $tmpName     = $fileInFiles['tmp_name'];
        $fileName    = $fileInFiles['name'];

        $info      = pathinfo($fileName);
        $extension = strtolower($info['extension']);


        $exts = $this->allowedExts;
        $mimes = $this->allowedMimes;

        if ($errorCode != UPLOAD_ERR_OK) {
            $this->addUploadError($model, $this->getName(), $errorCode);
            return;
        }

        if (!empty($this->maxFileSize) && $size > $this->maxFileSize) {
            $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_MAX_SIZE);
            return;
        }

        if (!empty($mimes) && !in_array($mime, $mimes)) {
            $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_INVALID_MIME);
            return;
        }

        if (!empty($exts) && !in_array($extension, $exts)) {
            $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_INVALID_EXT);
            return;
        }

        if (!is_dir($dir) && $create) {
            if (!@mkdir($dir, 0775, true)) {
                $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_CANT_CREATE_DIR);
                return;
            }
        }

        if (!is_writable($dir)) {
            $this->addUploadError($model, $this->getName(), UPLOAD_ERR_CANT_WRITE);
            return;
        }



        if (!is_uploaded_file($tmpName)) {
            $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_NO_UPLOADED_FILE);
            return;
        }

        $newFileName = '';


        if (!$isInsert && !empty($model->{$this->getName()})) {
            $curExt = strtolower(preg_replace('#.*\.#', '', $model->{$this->getName()}));
            if ($curExt == $extension) {
                $newFileName = $model->{$this->getName()};
            }
        }

        if (empty($newFileName))
            $newFileName = $this->parseFileName($model, $fileNamePattern, $extension, $size, $mime, $fileName);

        $nFilePath = $dir.DIRECTORY_SEPARATOR.$newFileName;
        $nFileDir  = dirname($nFilePath);
        if (!file_exists($nFileDir)) {
            mkdir($nFileDir, 0766, true);
        }
        if (!move_uploaded_file($tmpName, $nFilePath)) {
            $this->addUploadError($model, $this->getName(), self::UPLOAD_ERR_UNKNOWN_ERROR);
            return;
        }
        $model->{$this->getName()} = $newFileName;

        if (!empty($this->sizeField)) {
            $model->{$this->sizeField} =  $size;
        }
        if (!empty($this->mimeField)) {
            $model->{$this->mimeField} = $mime;
        }
        if (!empty($this->extField)) {
            $model->{$this->extField} = $extension;
        }
    }

    protected function addUploadError($model, $fieldName, $errorCode)
    {
        $message = false;
        switch ($errorCode) {
            case self::UPLOAD_ERR_CANT_CREATE_DIR:
                $message = $this->getErrCantCreateDirMessage();
                break;
            case self::UPLOAD_ERR_HAS_OTHER_ERRORS:
                $message = $this->getErrHasOtherErrorsMessage();
                break;
            case self::UPLOAD_ERR_INVALID_EXT:
                $message = $this->getErrInvalidExtMessage();
                break;
            case self::UPLOAD_ERR_INVALID_MIME:
                $message = $this->getErrInvalidMimeMessage();
                break;
            case self::UPLOAD_ERR_MAX_SIZE:
                $message = $this->getErrMaxSizeMessage();
                break;
            case self::UPLOAD_ERR_NO_UPLOADED_FILE:
                $message = $this->getErrNoUploadedFileMessage();
                break;
            case self::UPLOAD_ERR_UNKNOWN_ERROR:
                $message = $this->getErrUnknownErrorMessage();
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = $this->getErrCantWriteMessage();
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = $this->getErrFormSizeMessage();
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message = $this->getErrIniSizeMessage();
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = $this->getErrExtensionMessage();
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = $this->getErrPartialUploadMessage();
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = $this->getErrNoTmpDirMessage();
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = $this->getErrNoFileMessage();
                break;
        }
        if ($message !== false) {
            $this->setError($message);
            return true;
        }
    }


    public function afterDelete($success)
    {
        if ($success)
            $this->deleteFile();
    }

    protected function deleteFile()
    {
        $dir        = $this->dir;
        $fieldValue = $this->value;
        $filename   = $dir.DIRECTORY_SEPARATOR.$fieldValue;
        if (file_exists($filename) && is_writeable($filename)) {
            @unlink($filename);
        }
    }

    function parseFileName($model, $name, $ext, $size, $fileName)
    {
        if (empty($name))
            $name = '{d}{m}{y}{h}{i}{s}{md5}.{ext}';

        if (preg_match_all('#\{(.*?)\}#is', $name, $matches)) {
            $data = array();
            $data['uniqid'] = uniqid();
            $data['md5'] = md5($data['uniqid']);
            $data['ext'] = $ext;
            $data['size'] = $size;
            $data['name'] = $fileName;
            $data['time'] = time();
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
        return $name;
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

    protected function loadDefaultMessages()
    {
        $this->setErrCantWriteMessage('Falha ao tentar gravar arquivo no servidor.');
        $this->setErrExtensionMessage('Envio cancenlado por uma extensão do servidor.');
        $this->setErrFormSizeMessage('O tamanho do arquivo é maior que o especificado no formulário.');
        $this->setErrIniSizeMessage('O tamanho do arquivo é maior que o tamanho máximo permitido pelo servidor.');
        $this->setErrMaxSizeMessage('O tamanho do arquivo é maior que o permitido.');
        $this->setErrNoFileMessage('O arquivo precisa ser informado.');
        $this->setErrNoTmpDirMessage('Não foi encontrado uma pasta temporária para a transferência do arquivo.');
        $this->setErrPartialUploadMessage('O arquivo foi enviado parcialmente.');
        $this->setErrCantCreateDirMessage('O diretório que conterá o arquivo não pôde ser criado.');
        $this->setErrHasOtherErrorsMessage('Outros erros foram encontrados e o arquivo não foi processado.');
        $this->setErrNoUploadedFileMessage('O arquivo não foi enviado de forma correta.');
        $this->setErrInvalidExtMessage('O arquivo não possui uma extensão permitida.');
        $this->setErrInvalidMimeMessage('O arquivo não possui um tipo permitido.');
        $this->setErrUnknownErrorMessage('Ocorreu um erro desconhecido.');
    }

    public function setErrExtensionMessage($errExtensionMessage)
    {
        $this->errExtensionMessage = $errExtensionMessage;
    }

    public function setErrCantWriteMessage($errCantWriteMessage)
    {
        $this->errCantWriteMessage = $errCantWriteMessage;
    }

    public function setErrNoTmpDirMessage($errNoTmpDirMessage)
    {
        $this->errNoTmpDirMessage = $errNoTmpDirMessage;
    }

    public function setErrNoFileMessage($errNoFileMessage)
    {
        $this->errNoFileMessage = $errNoFileMessage;
    }

    public function setErrPartialUploadMessage($errPartialUploadMessage)
    {
        $this->errPartialUploadMessage = $errPartialUploadMessage;
    }

    public function setErrMaxSizeMessage($errMaxSizeMessage)
    {
        $this->errMaxSizeMessage = $errMaxSizeMessage;
    }

    public function setErrFormSizeMessage($errFormSizeMessage)
    {
        $this->errFormSizeMessage = $errFormSizeMessage;
    }

    public function setErrIniSizeMessage($errIniSizeMessage)
    {
        $this->errIniSizeMessage = $errIniSizeMessage;
    }

    public function getErrExtensionMessage()
    {
        return $this->errExtensionMessage;
    }

    public function getErrCantWriteMessage()
    {
        return $this->errCantWriteMessage;
    }

    public function getErrNoTmpDirMessage()
    {
        return $this->errNoTmpDirMessage;
    }

    public function getErrNoFileMessage()
    {
        return $this->errNoFileMessage;
    }

    public function getErrPartialUploadMessage()
    {
        return $this->errPartialUploadMessage;
    }

    public function getErrMaxSizeMessage()
    {
        return $this->errMaxSizeMessage;
    }

    public function getErrFormSizeMessage()
    {
        return $this->errFormSizeMessage;
    }

    public function getErrIniSizeMessage()
    {
        return $this->errIniSizeMessage;
    }

    public function setErrCantCreateDirMessage($errCantCreateDirMessage)
    {
        $this->errCantCreateDirMessage = $errCantCreateDirMessage;
    }

    public function setErrHasOtherErrorsMessage($errHasOtherErrorsMessage)
    {
        $this->errHasOtherErrorsMessage = $errHasOtherErrorsMessage;
    }

    public function getErrCantCreateDirMessage()
    {
        return $this->errCantCreateDirMessage;
    }

    public function getErrHasOtherErrorsMessage()
    {
        return $this->errHasOtherErrorsMessage;
    }

    public function setErrNoUploadedFileMessage($errNoUploadedFileMessage)
    {
        $this->errNoUploadedFileMessage = $errNoUploadedFileMessage;
    }

    public function getErrNoUploadedFileMessage()
    {
        return $this->errNoUploadedFileMessage;
    }

    public function setErrInvalidMimeMessage($errInvalidMimeMessage)
    {
        $this->errInvalidMimeMessage = $errInvalidMimeMessage;
    }

    public function setErrInvalidExtMessage($errInvalidExtMessage)
    {
        $this->errInvalidExtMessage = $errInvalidExtMessage;
    }

    public function getErrInvalidMimeMessage()
    {
        return $this->errInvalidMimeMessage;
    }

    public function getErrInvalidExtMessage()
    {
        return $this->errInvalidExtMessage;
    }

    public function setErrUnknownErrorMessage($errUnknownErrorMessage)
    {
        $this->errUnknownErrorMessage = $errUnknownErrorMessage;
    }

    public function getErrUnknownErrorMessage()
    {
        return $this->errUnknownErrorMessage;
    }

    public function toHtml(Field $field)
    {
        $input = \W5n\Html\HtmlBuilder::input($this->getName(), null, 'file');
        $input->addClass('input-upload');
        $this->applyValidationHtmlModifications($input);

        $value = $this->getValue();
        if (!empty($value)) {
            $url = self::getPrefixUri() . $this->dir . '/' . $value;
            $this->setInfo(sprintf('<a href="%s" target="_blank">%s</a>', $url, 'Ver arquivo'));
        }

        return $input;
    }


}