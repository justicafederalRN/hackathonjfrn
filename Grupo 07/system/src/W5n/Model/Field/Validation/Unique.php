<?php
namespace W5n\Model\Field\Validation;

class Unique extends \W5n\Model\Field\Validation\Validation
{
    protected $fields;
    
    public function __construct($fields = null, $modifyQueryCallback = null, $modifyFieldsCallback = null)
    {
        if (!is_array($fields) && !empty($fields)) {
            $fields = array($fields);
        }
        $this->fields = $fields;
        
        $this->setOption('modifyQueryCallback', $modifyQueryCallback);
        $this->setOption('modifyFieldsCallback', $modifyFieldsCallback);
    }
    
    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return $this->genderString($field, 'Este ', 'Esta ') 
            . $field->getLabel()
            . ' já existe.';
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        if (empty($this->fields)) {
            $this->fields = array($field->getName());
        }
        
        $pk        = $field->getModel()->getPrimaryKey();
        $tableName = $field->getModel()->getTable();
        $pkValue   = $field->getModel()->id();
        $db        = $field->getModel()->getConnection();
        $select    = $db->createQueryBuilder()->select('COUNT(*) as row_exists')->from($tableName);
        
        $queryCallback = $this->getOption('modifyQueryCallback');
        $fieldCallback = $this->getOption('modifyFieldsCallback');
        
        foreach ($this->fields as $f) {
            if (is_callable($fieldCallback)) {
                call_user_func($fieldCallback, $select, $field, $f, $this);
            } else {               
                $select->andWhere(
                    $f . '=' . $select->createPositionalParameter($field->getModel()->getField($f)->getValue(\W5n\Model\Model::OP_VALIDATE))
                );
            }
        }
        
        if (!empty($pkValue)) {
            $select->andWhere($pk . '!=' . $select->createPositionalParameter($pkValue));
        }
        
        if (is_callable($queryCallback)) {
            call_user_func($queryCallback, $select, $field, $this);
        }
        
        $result = $select->execute()->fetch();
        return !(bool)$result['row_exists'];
    }

}