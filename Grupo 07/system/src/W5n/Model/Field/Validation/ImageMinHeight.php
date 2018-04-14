<?php

namespace W5n\Model\Field\Validation;

class ImageMinHeight extends Validation
{

    protected $height;
    protected $errorMessage;

    public function skipIfEmpty()
    {
        return false;
    }

    public function __construct($height, $errorMessage = null)
    {
        $this->height       = $height;
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        if (!empty($this->errorMessage)) {
            return $this->errorMessage;
        }
        return 'A altura da imagem não pode ser menor que ' . $this->height . ' pixels.';
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
        $height = $this->height;

        if (is_callable($height)) {
            $height = call_user_func($height, $size[0], $size[1]);
        }

        return $size[1] >= $height;
    }


}
