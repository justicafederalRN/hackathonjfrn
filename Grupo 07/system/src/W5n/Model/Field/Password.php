<?php
namespace W5n\Model\Field;

class Password extends Text
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label);
    }

    public function beforeSetValue(&$value, $operation)
    {
        if (strlen($value) > 0 && $operation != \W5n\Model\Model::OP_DB_POPULATE) {
            $this->setOption('originalValue', $value);
            $oldValue = $value;
            $value    = password_hash($value, PASSWORD_BCRYPT);
            if ($value === false && $oldValue != $value) {
                $pass = new \PasswordHash(10, false);
                $value = $pass->HashPassword($oldValue);
            }

        } else {

        }
    }

    public function beforeSave($operation)
    {
        if (strlen($this->value) == 0) {
            $this->value = $this->getOption('dbValue');
        }
    }

    public function afterModelPopulate($operation)
    {
        if ($operation == \W5n\Model\Model::OP_DB_POPULATE) {
            $this->setOption('dbValue', $this->value);
        }
    }

    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->setAttr('type', 'password');
        $input->setAttr('value', '');
        $input->addClass('input-password')->removeClass('input-text');
        $this->applyValidationHtmlModifications($input);
        return $input;
    }


}
