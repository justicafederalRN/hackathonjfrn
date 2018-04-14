<?php
namespace W5n\Model\Field;


class Cnpj extends Mask
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label, '99.999.999/9999-99');
        $this->cnpj();
    }
    
    
}

