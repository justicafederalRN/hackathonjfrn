<?php
namespace W5n\Model\Field;

class HasMany extends \W5n\Model\Field\Field
{

    protected $loaded = false;
    protected $loadedValues = array();

    function __construct(
        $name, $referenceColumn,
        $instantiateFunction, $modifyFindQueryCallback = null
    ) {
        parent::__construct($name, $name);
        $this->setOption('instantiateFunction', $instantiateFunction);
        $this->setOption('modifyFindQueryCallback', $modifyFindQueryCallback);
        $this->setOption('referenceColumn', $referenceColumn);
        $this->setPersistent(false);
    }

    public function toHtml(Field $field)
    {
        $input = \W5n\Html\HtmlBuilder::tag('span');
        return $input;
    }

    public function getValue($operation = NULL)
    {
        if (empty($this->value)) {
            $this->loadRelated($this->getModel()->id());
            return $this->loadedValues;
        }
        return parent::getValue($operation);
    }

    /**
     *
     * @return \W5n\Model\Model
     */
    public function getOtherModel()
    {
        $instantiateFunction = $this->getOption('instantiateFunction');
        $object              = call_user_func($instantiateFunction, $this);
        return  $object;
    }

    protected function loadRelated($pkValue)
    {
        if (!$this->loaded && !empty($pkValue)) {
            $db     = $this->getModel()->getConnection();
            $model  = $this->getOtherModel();
            $query  = $db->createQueryBuilder()->select('*')->from($model->getTable());

            $callback = $this->getOption('modifyFindQueryCallback');

            if (!empty($callback) && is_callable($callback)) {
                call_user_func($callback, $query, $this);
            }
            $fkField = $this->getOption('referenceColumn');

            $query->where($fkField . '=' . $query->createPositionalParameter($pkValue));

            $result = $query->execute()->fetchAll();


            $this->setOption('otherTable', $model->getTable());
            $this->setOption('otherPrimaryKey', $model->getPrimaryKey());
            $this->loadedValues = $result;
            $this->loaded       = true;
        }
        return $this->loadedValues;
    }

    public function afterSave($success, $operation)
    {
        try {
            if ($success) {
                $this->loadRelated($this->getModel()->id());

                $otherPrimaryKey = $this->getOption('otherPrimaryKey');
                $otherTable      = $this->getOption('otherTable');

                $ids = array();

                foreach ($this->loadedValues as $v) {
                    $ids[] = $v[$otherPrimaryKey];
                }

                if (!is_array($this->value)) {
                    $this->value = array();
                }

                foreach ($this->value as $v) {
                    if (is_array($v)) {
                        $data = $v;
                        $v    = $this->getOtherModel();
                        $v->populateFromArray($data, true, true, \W5n\Model\Model::OP_DB_POPULATE);
                    }
                    if (isset($v->{$otherPrimaryKey})) {
                        $key = array_search($v->{$otherPrimaryKey}, $ids);
                        if ($key !== false) {
                            unset($ids[$key]);
                        }
                    }
                    $fkField = $this->getOption('referenceColumn');
                    $v->{$fkField} = $this->getModel()->id();
                    $v->save();
                }

                if (!empty($ids)) {
                    $db = $this->getModel()->getConnection();
                    $db->exec('DELETE FROM ' . $otherTable  . ' WHERE ' . $otherPrimaryKey . ' IN (' . implode(',', $ids) . ')');
                }
            }
        } catch (\Exception $ex) {
            $app = $this->getModel()->getApplication();
            $app['session']->getFlashBag()->add('warning', 'O item não pode ser excluído possui dados relacioandos.');
        }
    }

}
