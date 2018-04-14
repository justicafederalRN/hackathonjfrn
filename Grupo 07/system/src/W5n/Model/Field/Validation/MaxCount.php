<?php
namespace W5n\Model\Field\Validation;

class MaxCount extends \W5n\Model\Field\Validation\Validation
{

    public function __construct($value, $allowEquals, $errorMessage)
    {
        $this->setOption('value', $value);
        $this->setOption('allowEquals', $allowEquals);
        $this->setOption('errorMessage', $errorMessage);
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->getOption('errorMessage');
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if (!is_array($value)) {
            return true;
        }

        if ($this->getOption('allowEquals', true)) {
            return count($value) <= $this->getOption('value');
        } else {
            return count($value) < $this->getOption('value');
        }
    }
}
