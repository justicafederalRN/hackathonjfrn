<?php
namespace W5n\Model\Field;


class Money extends Mask
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label, '#.##0,00', true);
        $this->regex('#^([0-9]{1,3}.?)+(,([0-9]{1,2}))?$#');
    }
    
    public function beforeSetValue(&$value, $operation)
    {
        if (strlen($value) == 0) {
            $value = null;
        }
    }
    
    public function afterModelPopulate($operation)
    {
        parent::afterModelPopulate($operation);
        if ($operation == \W5n\Model\Model::OP_DB_POPULATE && !empty($this->value)) {
            if (is_numeric($this->value)) {
                $this->value = number_format($this->value, 2, ',', '.');
            }
        }
    }
    
    public function beforeSave($operation)
    {
        parent::beforeSave($operation);        
        $this->value = str_replace(',', '.', str_replace('.', '', $this->value));        
        
        
        
        if (strlen($this->value) == 0) {
            $this->value = null;
        }
    }

    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('text-right');
        return $input;
    }
    
    
}

