<?php
namespace W5n\Model\Field;

class Color extends Text
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label);
        $this->regex('/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/');
    }


    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        //$input->setAttr('type', 'color');
        $wrapper = new \W5n\Html\Tag();
        $wrapper->addClass('input-group input-color-container colorpicker-component');

        $input->addClass('input-color form-control');
        $wrapper->appendChild($input);
        $background = $this->getValue();
        $wrapper->appendText('<span class="input-group-addon"><i style="background-color: ' . (empty($background) ? 'black' : $background) . '; cursor:pointer;display:inline-block;width:16px;height:16px;"></i></span>');

        return $wrapper;
    }

}
