<?php
namespace W5n\Model\Field;

class DbOptions extends Options
{
    
    public function __construct(
        $name, $label, 
        $tableName, $valueField, $labelField, 
        $defaultMessage = null, $emptyMessage = null, 
        $modifyQueryCallback = null, $formatLabelCallback = null
    ) {
        parent::__construct($name, $label, array(), $defaultMessage, $emptyMessage);
        $this->setOption('tableName', $tableName);
        $this->setOption('valueField', $valueField);
        $this->setOption('labelField', $labelField);
        $this->setOption('modifyQueryCallback', $modifyQueryCallback);
        $this->setOption('formatLabelCallback', $formatLabelCallback);
    }
    
    public function getResults($searchTerm = null, $modifyQueryCallback = null)
    {
        $tableName     = $this->getOption('tableName');
        $valueField    = $this->getOption('valueField');
        $labelField    = $this->getOption('labelField');
        $queryCallback = $this->getOption('modifyQueryCallback');

        $db = $this->getModel()->getConnection();
        $select = $db->createQueryBuilder()->select($valueField, $labelField)
            ->from($tableName)
            ->orderBy($labelField);
        
        if (strlen($searchTerm) > 0) {
            $select->where($labelField . ' LIKE ' . $select->createPositionalParameter($searchTerm));
        }
        
        if (is_callable($queryCallback)) {
            call_user_func($queryCallback, $select, $searchTerm, $this);
        }
        
        if (is_callable($modifyQueryCallback)) {
            call_user_func($modifyQueryCallback, $select, $searchTerm, $this);
        }        
        
        $result = $select->execute()->fetchAll();

        $options = array();
        $formatLabelCallback = $this->getOption('formatLabelCallback');
        $cleanedLabel        = preg_replace('#^.*\.#', '', $labelField);
        
        foreach ($result as $r) {            
            $value = $r[$cleanedLabel];
            if (is_callable($formatLabelCallback)) {
                $value = call_user_func($formatLabelCallback, $value, $r);
            }
            $options[$r[$valueField]] = $value;
        }        
        return $options;
    }    
    
    public function getLabelField()
    {
        return $this->getOption('labelField');
    }
    
    public function getValueField()
    {
        return $this->getOption('valueField');
    }
    
    public function toHtml(Field $field)
    {
        $options = $this->getResults();
        $this->setOption('options', $options);
        
        return parent::toHtml($field);
    }
    
}