<?php
namespace W5n\Model\Field;


class LongText extends \W5n\Model\Field\Field
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label);
    }
    
    public function toHtml(Field $field)
    {
        $textarea = \W5n\Html\HtmlBuilder::tag('textarea');
        
        if ($this->hasError()) {
            $textarea->addClass('has-error');
        }
        
        $textarea->addClass('input-long-text');        
        $textarea->mergeAttributes($this->getOptions()->all());
        $textarea->appendText($this->getValue());
        $textarea->setAttr('rows', '4');
        $textarea->setPrettyPrint(false);
        $textarea->setAttr('name', $this->getName());
        $this->applyValidationHtmlModifications($textarea);
        
        return $textarea;
    }
    
    public function getDisplayValue()
    {
        return nl2br(strip_tags($this->getValue()));
    }
    
    public function getValue($operation = NULL)
    {
        return trim(parent::getValue($operation), "\n\t");
    }
    
    public function __toString()
    {
        return $this->toHtml($this);
    }
    
}

