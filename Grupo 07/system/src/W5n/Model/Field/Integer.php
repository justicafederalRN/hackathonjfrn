<?php
namespace W5n\Model\Field;


class Integer extends Mask
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label, '0#');
        $this->integer();
    }
    
    public function beforeSetValue(&$value, $operation)
    {
        if (strlen($value) == 0) {
            $value = null;
        } else {
            $value = intval($value);
        }
    }
    
    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-integer text-right');
        return $input;
    }
    
    
}

