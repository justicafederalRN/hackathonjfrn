<?php
namespace W5n\Model\Field;

class Options extends \W5n\Model\Field\Field
{
    
    function __construct(
        $name, $label, array $options, $defaultMessage = null,
        $emptyMessage = null
    ) {
        parent::__construct($name, $label);
        $this->setOption('options', $options);
        $this->setOption('defaultMessage', $defaultMessage);
        $this->setOption('emptyMessage', $emptyMessage);        
    }
    
    public function beforeSetValue(&$value, $operation)
    {
        if (!is_array($value) && strlen($value) == 0) {
            $value = null;
        }
        parent::beforeSetValue($value, $operation);
    }
    
    public function toHtml(Field $field)
    {
        $options        = $this->getOption('options', array());
        $defaultMessage = $this->getOption('defaultMessage');
        $emptyMessage   = $this->getOption('emptyMessage');
        if (empty($options) && !is_null($emptyMessage)) {
            $options = array('' => $emptyMessage);
        } elseif (!empty($options) && !is_null($defaultMessage)) {
            $options = array('' => $defaultMessage) + $options;
        }
                
        $select = \W5n\Html\HtmlBuilder::select($this->getName(), $options, $this->getValue());
        $select->addClass('input-options');        
        $this->applyValidationHtmlModifications($select);
        return $select;
    }
    
    public function getDisplayValue()
    {
        return $this->getValueLabel();
    }
    
    public function getValueLabel()
    {
        $options = $this->getOption('options', array());
        $value   = $this->getValue();
        return isset($options[$value]) ? $options[$value] : null;
    }
}

