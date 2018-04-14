<?php
namespace W5n\Model\Field\Validation;


class Mandatory extends \W5n\Model\Field\Validation\Validation
{
    protected $callback = null;
    
    public function skipIfEmpty()
    {
        return false;
    }
    
    public function __construct($callback = null)
    {
        $this->callback = $callback;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Campo obrigatório';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $isMandatory = true;
        if (is_callable($this->callback)) {
            $isMandatory = call_user_func($this->callback, $value, $field, $operation);
        }
        
        if (!$isMandatory) {
            return true;
        }
        
        if (is_array($value)) {
            return !empty($value);
        }
        
        if ($field instanceof \W5n\Model\Field\Uploadable) {
            $fieldName = $field->getName();
            $filled    = isset($_FILES[$fieldName]) 
                       && $_FILES[$fieldName]['error'] == UPLOAD_ERR_OK;
            
            if ($filled) {
                return true;
            } elseif ($operation == \W5n\Model\Model::OP_INSERT) {
                return false;
            }
        }
        
        $size = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
        
        return $size > 0;
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $value = $field->getValue();
        if (($field instanceof \W5n\Model\Field\Uploadable && !empty($value)) || !empty($this->callback)) {
            return;
        }
        $html->setAttr('required', 'required');
        
    }

}
