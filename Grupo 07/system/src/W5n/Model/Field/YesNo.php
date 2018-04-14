<?php
namespace W5n\Model\Field;

class YesNo extends \W5n\Model\Field\Options
{
    protected $yesNoOptions = array(
        '1' => 'Sim',
        '0' => 'Não'
    );
    
    public function __construct($name, $label, $defaultMessage = null)
    {
        parent::__construct($name, $label, $this->yesNoOptions, $defaultMessage);
    }
    
    public function setYesNoOptions(array $options)
    {
        $this->yesNoOptions = $options;
        return $this;
    }
    
    public function getYesNoOptions()
    {
        return $this->yesNoOptions;
    }
}

