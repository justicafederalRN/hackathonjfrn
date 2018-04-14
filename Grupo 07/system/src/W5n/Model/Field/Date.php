<?php
namespace W5n\Model\Field;

class Date extends Mask
{

    function __construct($name, $label, $displayFormat = 'd/m/Y', $saveFormat = 'Y-m-d')
    {
        parent::__construct($name, $label, '00/00/0000');
        $this->setOption('displayFormat', $displayFormat);
        $this->setOption('saveFormat', $saveFormat);
        $this->date($saveFormat);
    }

    public function getDisplayFormat()
    {
        return $this->getOption('displayFormat', 'd/m/Y');
    }

    public function getSaveFormat()
    {
        return $this->getOption('saveFormat', 'Y-m-d');
    }

    public function getValue($operation = NULL)
    {
        $value          = trim($this->value);
        if (empty($value)) {
            return null;
        }

        $displayFormat  = $this->getOption('displayFormat', 'd/m/Y');
        $saveFormat     = $this->getSaveFormat();
        $date           = \DateTime::createFromFormat($saveFormat, $value);
        if (empty($date)) {
            $date = \DateTime::createFromFormat($displayFormat, $value);
        }

        if (empty($date)) {
            return $value;
        }

        if (
               $operation == \W5n\Model\Model::OP_INSERT
            || $operation == \W5n\Model\Model::OP_UPDATE
            || $operation == \W5n\Model\Model::OP_VALIDATE
        ) {
            return $date->format($saveFormat);
        }

        return $date->format($displayFormat);
    }

    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-date');

        return $input;
    }


}
