<?php
namespace W5n\Model\Field\Validation;

class Exists extends \W5n\Model\Field\Validation\Validation
{
    protected $fkTable;
    protected $fkField;
    protected $db;
    
    public function __construct($fkTable, $fkField, $db = null)
    {
        $this->fkTable = $fkTable;
        $this->fkField = $fkField;
        $this->db      = $db;
    }
    
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->genderString($field, 'Este ', 'Esta ') 
            . $field->getLabel()
            . ' não existe.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        $db = $this->db;
        if (empty($this->db)) {
            $db = $field->getModel()->getConnection();
        }
        
        $select = $db->createQueryBuilder()->select('COUNT(*) as row_exists')->from($this->fkTable);
        $select->where($this->fkField. '=' . $select->createNamedParameter($value));
        $result = $select->execute()->fetch();
        return (bool)$result['row_exists'];
    }

}