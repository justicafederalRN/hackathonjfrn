<?php
namespace W5n\Model\Field\Validation;

class DateGreaterThan extends \W5n\Model\Field\Validation\Validation
{

    protected $invalidField = false;

    public function __construct($otherDateField, $allowEquals = true)
    {
        $this->setOption('other', $otherDateField);
        $this->setOption('allowEquals', $allowEquals);
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        if ($this->invalidField) {
            return 'Não é possível comparar uma data com um campo de outro tipo';
        }

        if (!$this->getOption('allowEquals')) {
            $verb = 'menor ou igual a ';
        } else {
            $verb = 'menor que ';
        }

        return '"' . $field->getLabel() . '"'
            . ' não pode ser ' . $verb
            . '"' . $field->getModel()->getField($this->getOption('other'))->getLabel() . '".';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $this->invalidField = false;
        $otherField = $field->getModel()->getField($this->getOption('other'));
        if (!($otherField instanceof \W5n\Model\Field\Date) && !($field instanceof \W5n\Model\Field\Datetime)) {
            $this->invalidField = true;
            return false;
        }
        $otherValue = $otherField->getValue();
        if (empty($otherValue)) {
            return true;
        }

        $thisDate  = \DateTime::createFromFormat($field->getDisplayFormat(), $value);
        $otherDate = \DateTime::createFromFormat($otherField->getDisplayFormat(), $otherValue);

        if (empty($thisDate) || empty($otherDate)) {
            return false;
        }


        if ($this->getOption('allowEquals')) {
            return $thisDate->getTimestamp() >= $otherDate->getTimestamp();
        } else {
            return $thisDate->getTimestamp() > $otherDate->getTimestamp();
        }
    }

}
