<?php
namespace W5n\Model\Field;

class Ean13 extends Mask
{
    function __construct($name, $label)
    {
        parent::__construct($name, $label, '9999999999999');
        $this->regex('#^[0-9]{13}$#')->ean13();
    }
    public function toHtml(Field $field)
    {
        $el = parent::toHtml($field);
        $el->addClass('text-right');
        return $el;
    }
}
