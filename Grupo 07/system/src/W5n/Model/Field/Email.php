<?php
namespace W5n\Model\Field;

class Email extends Text
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label);
        $this->email();
    }


    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-email');
        $input->setAttr('type', 'email');
        return $input;
    }

}

