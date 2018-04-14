<?php
namespace W5n\Model\Field;

class RichText extends LongText
{

    public function getValue($operation = NULL)
    {
        $value = parent::getValue($operation);
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        return $value;
    }

    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-richtext');
        return $input;
    }

}
