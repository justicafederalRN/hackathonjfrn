<?php
namespace W5n\Model\Field\Validation;

class Max extends \W5n\Model\Field\Validation\Validation
{

    public function __construct($value, $allowEquals = true)
    {
        $this->setOption('value', $value);
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
            . $this->getOption('value');
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if ($this->getOption('allowEquals', true)) {
            return floatval($value) <= $this->getOption('value');
        } else {
            return floatval($value) < $this->getOption('value');
        }        
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-lessthan', 'true');
        $html->setAttr('data-fv-lessthan-value', $this->getOption('value'));
        $html->setAttr('data-fv-lessthan-inclusive', $this->getOption('allowEquals', true) ? 'true' : 'false');        
    }

}