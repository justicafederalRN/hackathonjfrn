<?php
namespace W5n\Model\Field;

class Cnh extends \W5n\Model\Field\Options
{
    protected $cnhOptions = array(
        'A'  => 'A',
        'AB' => 'AB',
        'AC' => 'AC',
        'AD' => 'AD',
        'AE' => 'AE',
        'B'  => 'B',
        'C'  => 'C',
        'D'  => 'D',
        'E'  => 'E'
    );
    
    public function __construct($name, $label, $defaultMessage = null)
    {
        parent::__construct($name, $label, $this->cnhOptions, $defaultMessage);
    }
    
    public function setCnhOptions(array $options)
    {
        $this->cnhOptions = $options;
        return $this;
    }
    
    public function getCnhOptions()
    {
        return $this->cnhOptions;
    }
}

