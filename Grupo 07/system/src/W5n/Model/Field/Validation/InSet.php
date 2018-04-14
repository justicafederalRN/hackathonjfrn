<?php
namespace W5n\Model\Field\Validation;

class InSet extends \W5n\Model\Field\Validation\Validation
{
    protected $set = array();
    
    public function __construct(array $set)
    {
        parent::__construct();
        $this->set = $set;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return 'Valor inválido';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        return in_array($value, $this->set);
    }

}