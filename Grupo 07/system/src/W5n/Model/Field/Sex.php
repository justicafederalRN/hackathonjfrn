<?php
namespace W5n\Model\Field;

class Sex extends \W5n\Model\Field\Options
{
    protected $sexOptions = array(
        'm' => 'Masculino',
        'f' => 'Feminino'
    );
    
    public function __construct($name, $label, $defaultMessage = null)
    {
        parent::__construct($name, $label, $this->sexOptions, $defaultMessage);
    }
    
    public function setSexOptions(array $options)
    {
        $this->sexOptions = $options;
        return $this;
    }
    
    public function getSexOptions()
    {
        return $this->sexOptions;
    }
}

