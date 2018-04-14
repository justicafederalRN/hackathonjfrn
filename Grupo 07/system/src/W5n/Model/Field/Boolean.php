<?php
namespace W5n\Model\Field;

class Boolean extends \W5n\Model\Field\Field 
{

    function __construct($name, $label, $startValue = 0)
    {
        parent::__construct($name, $label);
        $this->setValue($startValue);
    }
    
    public function beforeSetValue(&$value, $operation)
    {
        if ($this->isReadOnly()) {
            return;
        }
        $value = $value ? 1 : 0;
    }
    
    public function setReadOnly($bool = true)
    {
        $this->setOption('readOnly', $bool);
        return $this;
    }
    
    public function getDisplayValue()
    {
        return $this->value ? 'Sim' : 'Não';
    }
    
    public function isReadOnly()
    {
        return $this->getOption('readOnly', false);
    }
    
    public function toHtml(Field $field)
    {
        $input = \W5n\Html\HtmlBuilder::input($this->getName(), 1, 'checkbox');
        if ($this->hasError()) {
            $input->addClass('has-error');
        }
        if ($this->getValue() == 1) {
            $input->setAttr('checked', 'checked');
        }
        
        if ($this->isReadOnly()) {
            $input->setAttr('disabled', 'disabled');
        }
       
        $input->before(\W5n\Html\HtmlBuilder::input($this->getName(), 0, 'hidden'));
        $input->addClass('input-boolean');
        $input->mergeAttributes($this->getOptions()->all());
        $this->applyValidationHtmlModifications($input);
        return $input;
    }
    
}