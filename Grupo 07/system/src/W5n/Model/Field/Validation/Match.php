<?php
namespace W5n\Model\Field\Validation;

class Match extends \W5n\Model\Field\Validation\Validation
{

    public function __construct($otherPasswordFieldName, $ignoreOnEmpty = true)
    {
        $this->setOption('otherField', $otherPasswordFieldName);
        $this->setOption('ignoreOnEmpty', $ignoreOnEmpty);
    }
    
    public function skipIfEmpty()
    {
        return $this->getOption('ignoreOnEmpty', true);
    }
    
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Valores diferentes.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $otherField = $field->getModel()->getField($this->getOption('otherField'));
        if ($otherField instanceof \W5n\Model\Field\Password) {
            $compareValue = $otherField->getOption('originalValue');
            if (version_compare(phpversion(), '5.3.7', '<')) {
                $pass = new \PasswordHash(10, false);
                return $pass->CheckPassword($compareValue, $value);
            } else {
                return password_verify($compareValue, $value);
            }
        } else {
            return $value == $otherField->getValue($operation);
        }
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $html->setAttr('data-fv-identical', 'true');
        $html->setAttr('data-fv-identical-field', $this->getOption('otherField'));
    }

}