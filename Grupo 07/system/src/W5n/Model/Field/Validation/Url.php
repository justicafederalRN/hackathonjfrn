<?php
namespace W5n\Model\Field\Validation;

class Url extends \W5n\Model\Field\Validation\Validation
{
    
    public function __construct()
    {
        
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'O valor informado para ' 
            . $this->genderString($field, 'o ', 'a ') 
            . $field->getLabel()
            . ' não é uma URL válida';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-uri', 'true');
    }
    
}