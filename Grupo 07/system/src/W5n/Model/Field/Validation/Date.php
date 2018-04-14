<?php
namespace W5n\Model\Field\Validation;


class Date extends \W5n\Model\Field\Validation\Validation
{

    public function __construct($format = 'Y-m-d')
    {
        $this->setOption('format', $format);
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Data inválida';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        
        $value = $field->getValue(\W5n\Model\Model::OP_INSERT);
        $date  = \DateTime::createFromFormat(
            $this->getOption('format', 'Y-m-d'), $value
        );
        return !empty($date);
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $disableJsValidation = $field->getOption('disableJsValidation', false);
        if (!$disableJsValidation) {
            $html->setAttr('data-fv-date', 'true');
            $html->setAttr('data-fv-date-format', 'DD/MM/YYYY');
            $html->setAttr('data-fv-trigger', 'blur focus');
        }
    }
    
}
