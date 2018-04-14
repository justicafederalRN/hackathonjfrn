<?php
namespace W5n\Model\Field\Validation;

class MandatoryOnInsert extends Mandatory
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if ($operation != \W5n\Model\Model::OP_INSERT) {
            return true;
        }
        return parent::validate($value, $field, $operation);
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        $id = $field->getModel()->id();
        if (empty($id)) {
            $html->setAttr('required', 'required');
        }
    }

}
