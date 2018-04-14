<?php
namespace W5n\Model\Field\Validation;

class Number extends \W5n\Model\Field\Validation\Validation
{
    
    public function __construct()
    {
        
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'O valor informado para ' 
            . $this->genderString($field, 'o ', 'a ') 
            . $field->getLabel()
            . ' deve ser um número válido.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $value = str_replace(',', '.', str_replace('.', '', $value));
        
        return is_numeric($value);
    }
    
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-numeric', 'true');
        $html->setAttr('data-fv-numeric-separator', ',');
    }

}