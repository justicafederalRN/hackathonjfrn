<?php
namespace W5n\Model\Field;

class DependentOptions extends Options
{

    public function __construct(
        $name,
        $label,
        $dependentField,
        $ajaxHandler,
        $fillHandler
    ) {
        parent::__construct($name, $label, []);
        $this->setOption('dependentField', $dependentField);
        $this->setOption('handler', $ajaxHandler);
        $this->setOption('fillHandler', $fillHandler);
    }

    public function toHtml(Field $field)
    {
        $handler        = $this->getOption('handler');
        $dependentField = $this->getOption('dependentField');
        $value          = $this->getModel()->getField($dependentField)->getValue();

        if (!empty($this->value)) {
            $fillHandler = $this->getOption('fillHandler');
            $options     = call_user_func($fillHandler, $value);
            $this->setOption('options', $options);
        }

        $html =  parent::toHtml($field);
        $html->setAttr('class', 'input-dependent-options');
        $html->setAttr('data-ajax-handler', $handler);
        $html->setAttr('data-dependent-field', $dependentField);

        $thisValue = $this->getValue();
        if (!empty($thisValue)) {
            $html->setAttr('data-dependent-value', $thisValue);
        }

        return $html;
    }
}
