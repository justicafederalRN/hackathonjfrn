<?php
namespace W5n\Model\Field;

use W5n\Model\Field\Field;
use W5n\Html\HtmlBuilder;


class Text extends Field
{

    function __construct($name, $label = null)
    {
        parent::__construct($name, $label);
    }

    public function toHtml(Field $field)
    {
        $input = HtmlBuilder::input($this->getName(), $this->getValue());

        if ($this->hasError()) {
            $input->addClass('is-invalid');
        }

        $input->addClass('input-text');
        $this->applyValidationHtmlModifications($input);
        return $input;
    }

}

