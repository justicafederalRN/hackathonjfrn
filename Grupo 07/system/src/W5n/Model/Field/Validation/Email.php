<?php
namespace W5n\Model\Field\Validation;

class Email extends \W5n\Model\Field\Validation\Validation
{
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Email inválido';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-emailaddress', 'true');        
    }

}