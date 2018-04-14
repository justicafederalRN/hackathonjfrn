<?php
namespace W5n\Model\Field\Validation;

class Regex extends \W5n\Model\Field\Validation\Validation
{
    protected $regex;
    
    public function __construct($regex)
    {
        $this->regex = $regex;
    }
    
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->genderString($field, 'O ', 'A ') . $field->getLabel() 
            . ' não possui um valor válido';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        return (bool)preg_match($this->regex, $value);
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        if (preg_match('/#(.*?)#.*/', $this->regex, $matches)) {
            $html->setAttr('data-fv-regexp', 'true');
            $html->setAttr('data-fv-regexp-regexp', $matches[1]);
        }
    }

}

