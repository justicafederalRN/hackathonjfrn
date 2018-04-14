<?php
namespace W5n\Model\Field;

class Datetime extends Text
{

    public function __construct(
        $name,
        $label,
        $displayFormat = 'd/m/Y H:i',
        $saveFormat = 'Y-m-d H:i:s'
    ) {
        parent::__construct($name, $label);
        $this->setOption('displayFormat', $displayFormat);
        $this->setOption('saveFormat', $saveFormat);
        $this->datetime($saveFormat);
    }

    public function getDisplayFormat()
    {
        return $this->getOption('displayFormat', 'd/m/Y H:i');
    }

    public function getSaveFormat()
    {
        return $this->getOption('saveFormat', 'Y-m-d H:i:s');
    }

    public function getValue($operation = null)
    {
        $value          = trim($this->value);
        if (empty($value)) {
            return null;
        }

        $displayFormat  = $this->getOption('displayFormat', 'd/m/Y H:i');
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
        $input->addClass('input-datetime');

        return $input;
    }
}
