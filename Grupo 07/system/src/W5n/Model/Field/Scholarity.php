<?php
namespace W5n\Model\Field;

class Scholarity extends \W5n\Model\Field\Options
{
    protected $scholarityOptions = array(
        'fundamental_incompleto' => 'Ensino Fundamental Incompleto', 
        'fundamental_completo'   => 'Ensino Fundamental Completo',  
        'medio_incompleto'       => 'Ensino Médio Incompleto', 
        'medio_completo'         => 'Ensino Médio Completo', 
        'superior_incompleto'    => 'Superior Incompleto', 
        'superior_completo'      => 'Superior Completo', 
        'pos_graduado'           => 'Pós Graduado', 
        'mestrado'               => 'Mestrado', 
        'doutorado'              => 'Doutorado', 
        'pos_doutorado'          => 'Pós Doutorado'
    );
    
    public function __construct($name, $label, $defaultMessage = null)
    {
        parent::__construct($name, $label, $this->scholarityOptions, $defaultMessage);
    }
    
    public function setScholarityOptions(array $options)
    {
        $this->scholarityOptions = $options;
        return $this;
    }
    
    public function getScholarityOptions()
    {
        return $this->scholarityOptions;
    }
}

