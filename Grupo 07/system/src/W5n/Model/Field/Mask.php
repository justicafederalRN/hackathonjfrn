<?php
namespace W5n\Model\Field;


class Mask extends Text
{

    function __construct($name, $label, $mask, $reverse = false)
    {
        parent::__construct($name, $label);
        $this->setOption('mask', $mask);
        $this->setOption('reverse', $reverse);
    }
    
    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-mask')
            ->setAttr('data-mask', $this->getOption('mask'));
        
        $input->setAttr('data-fv-trigger', 'keyup');
        
        if ($this->getOption('reverse', false)) {
            $input->setAttr('data-mask-reverse', 'true');
        }
        $this->applyValidationHtmlModifications($input);
        return $input;
    }
    
    
}

