<?php
namespace W5n\Model\Field;

use W5n\Model\Field\Field;

class Slug extends Field 
{
    
    protected $_source_field = null;
    protected $_updateable   = false;
    
    
    function __construct($source_field, $updateable = FALSE, $name = 'permalink', $label = 'Permalink')
    {
        $this->_source_field   = $source_field;
        $this->_updateable     = $updateable;
        parent::__construct($name, $label);
    }
    
    public function beforeSave($operation)
    {
        $model = $this->getModel();
        if (!$model->hasField($this->_source_field)) {
            $this->setError('Campo ' . $this->_source_field . ' não existe');
            return;
        }
        $slug = self::slug($model->getField($this->_source_field)->getValue());        
        if ($operation == \W5n\Model\Model::OP_INSERT || $this->_updateable) {
            $this->setValue($slug);
        }
    }    
    
    public static function slug($str) {
        $escaped = htmlentities((str_replace('"', '', $str)), ENT_COMPAT);
        $regex   = array('#&([aeiou])(grave|acute|circ|tilde|uml|slash);#is', '#&(c)cedil;#is', '#&(a)elig;#is', '#&amp;#is');
        $escaped = trim(preg_replace($regex, array('$1', '$1', '$1', 'e'), $escaped));
        $escaped = preg_replace('#[^a-z0-9_ -]#is', '', $escaped);
        $escaped = preg_replace('# +#', '-', $escaped);
        $escaped = preg_replace('#-{2,}#', '-', $escaped);
        return strtolower($escaped);
    }
    
}

