<?php
namespace W5n\Model\Field;


class Cpf extends Mask
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label, '999.999.999-99');
        $this->cpf();
    }
    
    
}

