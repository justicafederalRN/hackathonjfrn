<?php
namespace W5n\Model\Field\Validation;


class Datetime extends Date
{


    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $disableJsValidation = $field->getOption('disableJsValidation', false);
        if (!$disableJsValidation) {
            $html->setAttr('data-fv-date', 'true');
            $html->setAttr('data-fv-date-format', 'DD/MM/YYYY h:m');
            $html->setAttr('data-fv-trigger', 'blur focus');
        }
    }

}

