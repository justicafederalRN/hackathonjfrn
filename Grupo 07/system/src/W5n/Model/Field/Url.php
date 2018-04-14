<?php
namespace W5n\Model\Field;


class Url extends Text
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label);
        $this->url();
    }
    
    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-url');
        return $input;
    }
    
    
}

