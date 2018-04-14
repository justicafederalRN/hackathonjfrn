<?php
namespace W5n\Model\Field;

use W5n\Model\Field\Field;


class Serialized extends Field 
{

    function __construct($name)
    {
        parent::__construct($name, $name);
    }
    
    public function beforeSave($operation)
    {
        $this->value = serialize($this->value);
    }
    
    public function afterSave($success, $operation)
    {
        $this->value = unserialize($this->value);
    }
    
    public function afterModelPopulate($operation)
    {
        parent::afterModelPopulate($operation);
        if (!empty($this->value) && $operation == \W5n\Model\Model::OP_DB_POPULATE) {
            $this->value = unserialize($this->value);
        }
    }
    
    public function toHtml(Field $field)
    {
        return '';
    }
    
}

