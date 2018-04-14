<?php
namespace W5n\Model\Field\Validation;

class MaxLength extends \W5n\Model\Field\Validation\Validation
{
    protected $length;
    
    public function __construct($maxLength)
    {
        $this->length = $maxLength;
    }
    
    public function skipIfEmpty()
    {
        return false;
    }
    
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        
        return $this->genderString($field, 'O ', 'A ') 
            . $field->getLabel()
            . ' deve ser ser menor ou igual a ' . $this->length . ' caracteres.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $length = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
        
        return $length <= $this->length; 
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('maxlength', $this->length);
    }
    
}