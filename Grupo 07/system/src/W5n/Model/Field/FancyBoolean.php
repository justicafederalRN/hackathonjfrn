<?php
namespace W5n\Model\Field;

use W5n\Html\HtmlBuilder;

class FancyBoolean extends Field
{

    function __construct($name, $label, $startValue = 0)
    {
        parent::__construct($name, $label);
        $this->setValue($startValue);
    }

    public function beforeSetValue(&$value, $operation)
    {
        if ($this->isReadOnly()) {
            return;
        }

        $value = empty($value) || intval($value) == 0 ? '0' : '1';
    }

    public function setReadOnly($bool = true)
    {
        $this->setOption('readOnly', $bool);
        return $this;
    }

    public function getDisplayValue()
    {
        return $this->value ? 'Sim' : 'Não';
    }

    public function isReadOnly()
    {
        return $this->getOption('readOnly', false);
    }

    public function toHtml(Field $field)
    {
        $input = HtmlBuilder::input($this->getName(), 1, 'checkbox');
        if ($this->hasError()) {
            $input->addClass('has-error');
        }
        if ($this->getValue() == 1) {
            $input->setAttr('checked', 'checked');
        }

        if ($this->isReadOnly()) {
            $input->setAttr('disabled', 'disabled');
        }

        $container = HtmlBuilder::tag('div');
        $label = HtmlBuilder::tag('label');

        $container->addClass('form-control fancy-boolean');

        $label->appendText($this->getLabel());

        $container->appendChild($label);

        $value = $this->getValue();

        $yesOption = HtmlBuilder::input($this->getName(), 1, 'radio');
        $this->applyValidationHtmlModifications($yesOption);

        if ($value === '1') {
            $yesOption->setAttr('checked', 'checked');
        }

        $noOption  = HtmlBuilder::input($this->getName(), 0, 'radio');
        $this->applyValidationHtmlModifications($noOption);
        if ($value === '0') {
            $noOption->setAttr('checked', 'checked');
        }

        $yesOption->addClass('fancy-boolean-option fancy-boolean-option-yes');
        $noOption->addClass('fancy-boolean-option fancy-boolean-option-no');

        $yesLabel = HtmlBuilder::tag('label');
        $noLabel  = HtmlBuilder::tag('label');

        $yesLabel->addClass('fancy-boolean-option-label');
        $noLabel->addClass('fancy-boolean-option-label');

        $yesLabel->appendChild($yesOption);
        $yesLabel->appendText(' Sim');

        $noLabel->appendChild($noOption);
        $noLabel->appendText(' Não');

        $optionsContainer = HtmlBuilder::tag('div');
        $optionsContainer->setAttr('class', 'pull-right');
        $optionsContainer->appendChild($yesLabel);
        $optionsContainer->appendChild($noLabel);


        $container->appendChild($optionsContainer);

        return $container;
    }

}