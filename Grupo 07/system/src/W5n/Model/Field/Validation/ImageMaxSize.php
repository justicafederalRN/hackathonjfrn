<?php

namespace W5n\Model\Field\Validation;

class ImageMaxSize extends Validation
{
    protected $size;
    protected $errorMessage;

    public function skipIfEmpty()
    {
        return false;
    }

    public function __construct($size, $errorMessage = null)
    {
        $this->size         = $size;
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        if (!empty($this->errorMessage)) {
            return $this->errorMessage;
        }

        return 'O tamanho do arquivo não pode ser maior que ' . $this->height . ' bytes.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if (!isset($_FILES[$field->getName()]) || $_FILES[$field->getName()]['error'] != UPLOAD_ERR_OK) {
            return true;
        }

        $size = $_FILES[$field->getName()]['size'];

        $maxSize = $this->size;

        if (is_callable($maxSize)) {
            $maxSize = call_user_func($maxSize, $_FILES[$field->getName()]);
        }

        return $size <= $size;
    }


}
