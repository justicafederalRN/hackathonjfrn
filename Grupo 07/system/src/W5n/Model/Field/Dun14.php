<?php
namespace W5n\Model\Field;

class Dun14 extends Mask
{
    function __construct($name, $label)
    {
        parent::__construct($name, $label, '99999999999999');
        $this->regex('#^[0-9]{14}$#')->dun14();
    }
    public function toHtml(Field $field)
    {
        $el = parent::toHtml($field);
        $el->addClass('text-right');
        return $el;
    }
}
