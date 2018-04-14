<?php
namespace W5n\Model\Field;

use W5n\Model\Field\Field;
use W5n\Html\HtmlBuilder;


class Display extends Field 
{

    function __construct($name, $label, $displayCallback, $emptyPlaceholder = '')
    {
        $this->setPersistent(false);
        parent::__construct($name, $label);
        $this->setOption('displayCallback', $displayCallback);
        $this->setOption('emptyPlaceholder', $displayCallback);
        
    }
    
    protected function getDisplayContent()
    {
        if (!is_callable($this->getOption('displayCallback'))) {
            throw new \LogicException(
                sprintf(
                    'Display callback is not callable for field "%s"', 
                    $this->getName()
                )
            );
        }
        $content = call_user_func($this->getOption('displayCallback'), $this->getModel(), $this);
        if (is_null($content)){
            $content = $this->getOption('emptyPlaceholder');
        }
        return $content;
    }
    
    public function toHtml(Field $field)
    {
        
        $input = HtmlBuilder::tag('div');
        $input->addClass('input-display');
        
        if ($this->hasError()) {
            $input->addClass('has-error');
        }
        
        $this->applyValidationHtmlModifications($input);
        $input->appendText($this->getDisplayContent());
        return $input;
    }
    
}

