<?php
namespace W5n\Model\Field\Validation;


class ValidateImage extends \W5n\Model\Field\Validation\Validation
{
    protected $callback = null;

    public function skipIfEmpty()
    {
        return false;
    }

    public function __construct()
    {}

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->getOption('message', 'Imagem Inválida');
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if (!($field instanceof \W5n\Model\Field\Uploadable)) {
            return true;
        }
        $fieldName = $field->getName();
        $errorCode = $_FILES[$fieldName]['error'];
        $fileName  = $_FILES[$fieldName]['name'];

        if ($errorCode != UPLOAD_ERR_OK) {
            $this->setOption('message', $this->getErrorByCode($errorCode));

            return false;
        }
        return true;
    }

    private function getErrorByCode($errorCode)
    {
        $message = false;
        switch ($errorCode) {
            case \UPLOAD_ERR_FORM_SIZE:
                return 'O tamanho do arquivo é maior que o permitido.';
            case \UPLOAD_ERR_INI_SIZE:
                return 'O tamanho do arquivo é maior que o tamanho máximo permitido.';
            case \UPLOAD_ERR_NO_FILE:
                return 'O arquivo deve ser informado.';
            case \UPLOAD_ERR_NO_IMAGE:
                return 'O arquivo não é uma imagem';
            case \UPLOAD_ERR_NO_TMP_DIR:
                return 'O arquivo não é uma imagem';
            case \UPLOAD_ERR_PARTIAL:
                return 'O arquivo não é uma imagem';
        }

        return null;

    }
}
