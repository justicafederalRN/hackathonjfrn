<?php
namespace W5n\Model\Field;

class Time extends Text
{

    public function __construct($name, $label, $displayFormat = 'H:i', $saveFormat = 'H:i:s')
    {
        parent::__construct($name, $label);
        $this->setOption('displayFormat', $displayFormat);
        $this->setOption('saveFormat', $saveFormat);
    }

    public function getDisplayFormat()
    {
        return $this->getOption('displayFormat', 'H:i');
    }

    public function getSaveFormat()
    {
        return $this->getOption('saveFormat', 'H:i:s');
    }

    public function getValue($operation = null)
    {
        $value          = trim($this->value);
        if (empty($value)) {
            return null;
        }

        $displayFormat  = $this->getOption('displayFormat', 'H:i');
        $saveFormat     = $this->getSaveFormat();
        $date           = \DateTime::createFromFormat($saveFormat, $value);
        if (empty($date)) {
            $date = \DateTime::createFromFormat($displayFormat, $value);
        }

        if (empty($date)) {
            return $value;
        }

        if ($operation == \W5n\Model\Model::OP_INSERT
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
        $input->addClass('input-time');

        return $input;
    }
}
