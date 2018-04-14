<?php
namespace W5n\Model\Field;

class Habtm extends \W5n\Model\Field\Field {

    function __construct($name, $label, $middleTable, $middleThisFk, $middleOtherFk, $displayField, $middleTablePrimaryKey = null, $app = null)
    {
        parent::__construct($name, $label);
        $this->setOption('otherModuleKey', $name);
        $this->setOption('middleTable', $middleTable);
        $this->setOption('middleForeignKey', $middleThisFk);
        $this->setOption('middleOtherForeignKey', $middleOtherFk);
        $this->setOption('middleTablePrimaryKey', $middleTablePrimaryKey);
        $this->setOption('displayField', $displayField);
        if (!is_null($app)) {
            $this->setOption('app', $app);
        }
        $this->setPersistent(false);
        $this->setValue(array());
    }

    public function toHtml(Field $field)
    {
        $db           = $this->getModel()->getConnection();
        $displayField = $this->getOption('displayField');
        $model        = $this->createOtherModel($db);
        $pk           = $model->getPrimaryKey();

        $options = $this->getItems();


        $container = \W5n\Html\HtmlBuilder::tag('select');
        $container->addClass('input-habtm');
        $container->setAttr('multiple', 'multiple');
        $container->setAttr('name', $this->getName() . '[]');

        $ids = $this->getValue();
        $isReadOnly = $this->isReadOnly();

        if ($isReadOnly) {
            $container->setAttr('readonly', 'readonly');
        }

        foreach ($options as $idx => $opt) {
            $itemContainer = $container->appendTag('option');
            if ($opt instanceof \W5n\Model\Model) {
                $opt = $opt->toArray();
            }
            $itemId = $opt[$model->getPrimaryKey()];

            $itemContainer->setAttr('value', $itemId);

            if (in_array($itemId, $ids)) {
                $itemContainer->setAttr('selected', 'selected');
            }
            $itemContainer->appendText($opt[$displayField]);
        }
        $this->applyValidationHtmlModifications($container);
        return $container;
    }

    protected function getItems($onlySelected = false)
    {
        $db           = $this->getModel()->getConnection();
        $displayField = $this->getOption('displayField');
        $modifyQuery  = $this->getOption('modifyQuery');
        $model        = $this->createOtherModel($db);
        $pk           = $model->getPrimaryKey();

        $selectedIds = null;
        if ($onlySelected) {
            $selectedIds = $this->getOthersIds($this->getModel()->id());
        }

        $options = $model->findAll(
            array(),
            function(\Doctrine\DBAL\Query\QueryBuilder $sel) use ($pk, $displayField, $selectedIds, $onlySelected, $modifyQuery)
            {
                $sel->orderBy($displayField);
                $sel->select($pk, $displayField);
                if ($onlySelected && !empty($selectedIds)) {
                    $sel->andWhere($pk . ' IN (' . implode(', ', $selectedIds) . ')');
                }
                if (is_callable($modifyQuery)) {
                    call_user_func($modifyQuery, $sel);
                }
            }
        );

        return $options;
    }

    public function getValue($operation = NULL)
    {
        if (empty($this->value)) {
            return array();
        }
        return parent::getValue($operation);
    }

    /**
     * @return \W5n\Application
     */
    protected function getApp()
    {
        return $this->getOption('app', \W5n\Application::getDefault());
    }

    /**
     * @param \Zend_Db_Adapter_Abstract $db
     * @return \W5n\Model\Model
     */
    protected function createOtherModel(\Doctrine\DBAL\Connection $db)
    {
        $app        = $this->getApp();
        $otherModel = $this->getOtherConfig('model');
        $refClass   = new \ReflectionClass($otherModel);

        return $refClass->newInstance($app);
    }

    /**
     *
     * @return \Zend_Config
     */
    protected function getOtherConfig($var = null, $value = null)
    {
        $otherModule = $this->getOption('otherModuleKey');
        $config      = $this->getApp()->loadConfig($otherModule);
        if (empty($var)) {
            return $config;
        }

        return isset($config[$var]) ? $config[$var] : $value;
    }

    public function afterSave($success, $operation)
    {
        if (!$success) {
            return;
        }

        $middleTable = $this->getOption('middleTable');
        $thisId      = $this->getModel()->id();
        $db          = $this->getModel()->getConnection();
        $otherPk     = $this->getOption('middleOtherForeignKey');
        $toDelete    = $this->getOthersIds($this->getModel()->id());



        $toInsert = array();

        foreach ($this->value as $id) {
            $key = array_search($id, $toDelete);
            if ($key !== false) {
                unset($toDelete[$key]);
            } else {
                $toInsert[] = (int)$id;
            }
        }

        if (!empty($toDelete)) {
            $stmt = $db->prepare(
                sprintf(
                    'DELETE FROM %s WHERE %s=? AND %s IN (' . implode(', ', $toDelete) . ')',
                    $middleTable,
                    $this->getOption('middleForeignKey'),
                    $otherPk
                )
            );
            $stmt->execute(array($thisId));
        }

        if (!empty($toInsert)) {
            foreach ($toInsert as $id) {
               $this->insertIntoMiddleTable($thisId, $id);
            }
        }

    }

    public function beforeModelPopulate($data, $operation)
    {
        parent::beforeModelPopulate($data, $operation);
        if ($operation == \W5n\Model\Model::OP_DB_POPULATE
         && !empty($data[$this->getModel()->getPrimaryKey()])) {
            $this->setValue($this->getOthersIds($data[$this->getModel()->getPrimaryKey()]));
        } elseif (empty($data[$this->getName()]))  {
            $this->value = array();
        }
    }
    /*
    public function get_relation_objects()
    {
        $values = $this->get_value();
        if (empty($values))
            return array();

        $other_object  = new $this->_other_model;
        $result        = Db::select()
                            ->from($other_object->get_table())
                            ->where($other_object->get_primary_key(), 'IN', $values)
                            ->execute($this->get_model()->get_database());
        $out = array();
        foreach ($result as $r) {
            $other_object  = new $this->_other_model;
            $other_object->populate_from_array($r, TRUE, TRUE, W_Model::OP_DB_POPULATE);
            $out[] = $other_object;
        }
        return $out;
    }
    */

    public function setReadOnly($bool)
    {
        $this->setOption('readOnly', $bool);
    }

    public function isReadOnly()
    {
        return $this->getOption('readOnly', false);
    }

    protected function getOthersIds($thisId)
    {
        $data = $this->getOthersData($thisId);
        if (empty($data)) {
            return $data;
        }
        $out = array();
        foreach ($data as $r) {
            $out[] = $r[$this->getOption('middleOtherForeignKey')];
        }
        return $out;
    }

    protected function getOthersData($id)
    {
        if (empty($id)) {
            return array();
        }

        $db    = $this->getModel()->getConnection();
        $model = $this->getOtherConfig('model');
        $key   = $this->getOption('middleForeignKey');
        $query = $db->createQueryBuilder()->select('*')->from($this->getOption('middleTable'));
        $query->where($key . '=' . $query->createNamedParameter($id));
        $data  = $query->execute()->fetchAll();

        return $data;
    }

    protected function insertIntoMiddleTable($thisId, $otherId)
    {
        $db          = $this->getModel()->getConnection();
        $middleTable = $this->getOption('middleTable');
        $otherPk     = $this->getOption('middleOtherForeignKey');
        $thisPk      = $this->getOption('middleForeignKey');

        $data = array(
            $thisPk  => $thisId,
            $otherPk => $otherId
        );



        return $db->insert($middleTable, $data);
    }

    public function getDisplayValue()
    {
        $values = $this->getItems(true);
        if (empty($values)) {
            return null;
        }

        $out          = array();
        $displayField = $this->getOption('displayField');

        foreach ($values as $v) {
            $out[] = $v[$displayField];
        }

        return implode(', ', $out);
    }
}