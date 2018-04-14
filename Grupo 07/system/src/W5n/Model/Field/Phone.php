<?php
namespace W5n\Model\Field;


class Phone extends Text
{

    function __construct($name, $label)
    {
        parent::__construct($name, $label);
    }

    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->setAttr('type', 'tel');
        $input->addClass('input-phone');
        $input->setAttr('data-fv-regexp', 'true');
        $input->setAttr('data-fv-regexp-regexp', '^(\(?[0-9]{2}\)?)? ?[0-9]{4,5}-?[0-9]{4,5}$');
        $input->setAttr('data-fv-regexp-message', 'Por favor informe um telefone válido');
        $input->setAttr('data-fv-trigger', 'blur');
        return $input;
    }

}

