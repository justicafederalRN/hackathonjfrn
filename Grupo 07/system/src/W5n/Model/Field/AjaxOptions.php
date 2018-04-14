<?php
namespace W5n\Model\Field;

class AjaxOptions extends Options
{

    public function __construct(
        $name,
        $label,
        $ajaxHandler,
        $fillHandler
    ) {
        parent::__construct($name, $label, []);
        $this->setOption('handler', $ajaxHandler);
        $this->setOption('fillHandler', $fillHandler);
    }

    public function toHtml(Field $field)
    {
        $handler     = $this->getOption('handler');
        $value       = $this->getValue();
        if (!empty($this->value)) {
            $fillHandler = $this->getOption('fillHandler');
            $options     = call_user_func($fillHandler, $value);
            $this->setOption('options', $options);
        }

        $html =  parent::toHtml($field);
        $html->setAttr('class', 'input-ajax-options');
        $html->setAttr('data-ajax-handler', $handler);

        return $html;
    }
}
