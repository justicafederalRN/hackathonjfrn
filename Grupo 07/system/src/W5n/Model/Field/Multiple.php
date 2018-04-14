<?php
namespace W5n\Model\Field;

use W5n\Html\HtmlBuilder;
use W5n\Model\Model;

class Multiple extends Field
{

    function __construct(
        $name, $label, array $options
    ) {
        parent::__construct($name, $label);
        $this->setOption('options', $options);
    }

    public function beforeSetValue(&$value, $operation)
    {
        if (!is_array($value) && strlen($value) == 0) {
            $value = null;
        }
        parent::beforeSetValue($value, $operation);
    }
    public function beforeSave($operation)
    {
        if (!empty($this->value)) {
            $this->value = serialize($this->value);
        }
    }

    public function afterSave($success, $operation)
    {
        if (!empty($this->value)) {
            $this->value = unserialize($this->value);
        }
    }

    public function afterModelPopulate($operation)
    {
        if (!empty($this->value) && $operation == Model::OP_DB_POPULATE) {
            $this->value = unserialize($this->value);
        }
    }

    public function toHtml(Field $field)
    {
        $options        = $this->getOption('options', array());
        $defaultMessage = $this->getOption('defaultMessage');
        $emptyMessage   = $this->getOption('emptyMessage');
        if (empty($options) && !is_null($emptyMessage)) {
            $options = array('' => $emptyMessage);
        } elseif (!empty($options) && !is_null($defaultMessage)) {
            $options = array('' => $defaultMessage) + $options;
        }

        $div = HtmlBuilder::tag('div', ['class' => 'input-multiple-container']);
        foreach ($options as $value => $option) {
            $label    = $div->appendTag('label', ['style' => 'display:block;']);
            $checkbox = $label->appendTag('input', ['type' => 'checkbox']);
            $checkbox->setAttr('name', $this->getName() . '[]');
            $checkbox->setAttr('value', $value);
            if (in_array($value, $this->value)) {
                $checkbox->setAttr('checked', 'checked');
            }
            $label->appendText(' ' . $option);
            $label->addClass('input-multiple-item');
        }
        return $div;
    }

    public function getDisplayValue()
    {
        return $this->getValueLabel();
    }

    public function getValueLabel()
    {
        $options = $this->getOption('options', array());
        $value   = $this->getValue();
        $result  = [];
        if (!empty($value) && is_array($value)) {
            foreach ($value as $label) {
                if (isset($options[$label])) {
                    $result[] = $options[$label];

                }
            }
        }
        return implode(', ', $result);
    }
}

