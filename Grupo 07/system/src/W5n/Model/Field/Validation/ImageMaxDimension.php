<?php
namespace W5n\Model\Field\Validation;

class ImageMaxDimension extends Validation
{

    protected $dimension;

    public function skipIfEmpty()
    {
        return false;
    }

    public function __construct($dimension)
    {
        $this->dimension = $dimension;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'A largura e a altura da imagem não podem ser maiores que ' . $this->dimension . ' pixels.';
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


        return $size[0] <= $this->dimension && $size[1] <= $this->dimension;
    }

}
