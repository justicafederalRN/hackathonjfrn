<?php

namespace W5n\Model\Field\Validation;

class ImageMinWidth extends Validation
{

    protected $width;
    protected $errorMessage;

    public function skipIfEmpty()
    {
        return false;
    }

    public function __construct($width, $errorMessage = null)
    {
        $this->width       = $width;
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        if (!empty($this->errorMessage)) {
            return $this->errorMessage;
        }
        return 'A largura da imagem não pode ser menor que ' . $this->width . ' pixels.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if (!isset($_FILES[$field->getName()]) || $_FILES[$field->getName()]['error'] != UPLOAD_ERR_OK) {
            return true;
        }

        $name = $_FILES[$field->getName()]['tmp_name'];

        getimagesize($name);
        $size = getimagesize($name);

        if (empty($size)) {
            return false;
        }
        $width = $this->width;

        if (is_callable($width)) {
            $width = call_user_func($width, $size[0], $size[1]);
        }

        return $size[0] >= $width;
    }


}
