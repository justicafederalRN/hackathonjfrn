<?php
namespace W5n\Model\Field\Validation;

class LessThan extends \W5n\Model\Field\Validation\Validation
{

    public function __construct($otherDateField, $allowEquals = true)
    {
        $this->setOption('other', $otherDateField);
        $this->setOption('allowEquals', $allowEquals);
    }
    
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        if (!$this->getOption('allowEquals')) {
            $verb = 'maior ou igual a ';
        } else {
            $verb = 'maior que ';
        }
        
        return '"' . $field->getLabel() . '"'
            . ' não pode ser ' . $verb 
            . '"' . $field->getModel()->getField($this->getOption('other'))->getLabel() . '".';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $otherField = $field->getModel()->getField($this->getOption('other'));
        $otherValue = $otherField->getValue($operation);
        $thisValue  = $field->getValue($operation);
        
        if (empty($thisValue) || empty($otherValue)) {
            return false;
        } 
        
        if ($this->getOption('allowEquals')) {
            return $thisValue <= $otherValue;
        } else {
            return $thisValue < $otherValue;
        }
    }

}