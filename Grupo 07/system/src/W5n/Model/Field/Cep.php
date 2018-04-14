<?php
namespace W5n\Model\Field;


class Cep extends Mask
{
    function __construct($name, $label)
    {
        parent::__construct($name, $label, '99999-999');
        $this->regex('#([0-9]{8}|[0-9]{5}-[0-9]{3})#');
    }
}

