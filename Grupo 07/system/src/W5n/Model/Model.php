<?php
namespace W5n\Model;

use W5n\Entity;
use W5n\Application;
use W5n\Model\Field\Field;
use W5n\Exception;

/**
 * Business Model Base Class
 * @method \W5n\Model\Field\AjaxOptions ajaxOptions($name, $label, $ajaxHandler, $fillHandler) Add AjaxOptions field to model
 * @method \W5n\Model\Field\Boolean boolean($name, $label, $startValue = 0) Add Boolean field to model
 * @method \W5n\Model\Field\Cep cep($name, $label) Add Cep field to model
 * @method \W5n\Model\Field\Cnh cnh($name, $label, $defaultMessage = null) Add Cnh field to model
 * @method \W5n\Model\Field\Cnpj cnpj($name, $label) Add Cnpj field to model
 * @method \W5n\Model\Field\Color color($name, $label) Add Color field to model
 * @method \W5n\Model\Field\Cpf cpf($name, $label) Add Cpf field to model
 * @method \W5n\Model\Field\Date date($name, $label, $displayFormat = 'd/m/Y', $saveFormat = 'Y-m-d') Add Date field to model
 * @method \W5n\Model\Field\Datetime datetime($name, $label, $displayFormat = 'd/m/Y H:i', $saveFormat = 'Y-m-d H:i:s') Add Datetime field to model
 * @method \W5n\Model\Field\DbOptions dbOptions($name, $label, $tableName, $valueField, $labelField, $defaultMessage = null, $emptyMessage = null, $modifyQueryCallback = null, $formatLabelCallback = null) Add DbOptions field to model
 * @method \W5n\Model\Field\DependentOptions dependentOptions($name, $label, $dependentField, $ajaxHandler, $fillHandler) Add DependentOptions field to model
 * @method \W5n\Model\Field\Display display($name, $label, $displayCallback, $emptyPlaceholder = '') Add Display field to model
 * @method \W5n\Model\Field\Dun14 dun14($name, $label) Add Dun14 field to model
 * @method \W5n\Model\Field\Ean13 ean13($name, $label) Add Ean13 field to model
 * @method \W5n\Model\Field\Email email($name, $label) Add Email field to model
 * @method \W5n\Model\Field\FancyBoolean fancyBoolean($name, $label, $startValue = 0) Add FancyBoolean field to model
 * @method \W5n\Model\Field\FancyUpload fancyUpload($name, $label, $dir, $fileName = null, $allowedExts = [], $allowedMimes = [], $maxFileSize = null, $sizeField = null, $extField = null, $mimeField = null, $createDir = true) Add FancyUpload field to model
 * @method \W5n\Model\Field\Field field($name, $label = null) Add Field field to model
 * @method \W5n\Model\Field\Habtm habtm($name, $label, $middleTable, $middleThisFk, $middleOtherFk, $displayField, $middleTablePrimaryKey = null, $app = null) Add Habtm field to model
 * @method \W5n\Model\Field\HasMany hasMany($name, $referenceColumn, $instantiateFunction, $modifyFindQueryCallback = null) Add HasMany field to model
 * @method \W5n\Model\Field\Image image($name, $label, $w = null, $h = null, $mode = 'RKC', $dir = null, $quality = 100, $imageName = null, $createDir = true) Add Image field to model
 * @method \W5n\Model\Field\Integer integer($name, $label) Add Integer field to model
 * @method \W5n\Model\Field\LongText longText($name, $label) Add LongText field to model
 * @method \W5n\Model\Field\Map map($name, $label, $searchByAdress = true, $startLat = 0, $startLng = 0, $startZoom = 14, $latField = 'latitude', $lngField = 'longitude', $zoomField = 'zoom') Add Map field to model
 * @method \W5n\Model\Field\Mask mask($name, $label, $mask, $reverse = false) Add Mask field to model
 * @method \W5n\Model\Field\Money money($name, $label) Add Money field to model
 * @method \W5n\Model\Field\Multiple multiple($name, $label, $options) Add Multiple field to model
 * @method \W5n\Model\Field\Options options($name, $label, $options, $defaultMessage = null, $emptyMessage = null) Add Options field to model
 * @method \W5n\Model\Field\Password password($name, $label) Add Password field to model
 * @method \W5n\Model\Field\Phone phone($name, $label) Add Phone field to model
 * @method \W5n\Model\Field\Repeater repeater($name, $label, $referenceColumn, $instantiateFunction, $itemLayout, $mode = 1, $emptyMessage = null, $modifyFindQueryCallback = null) Add Repeater field to model
 * @method \W5n\Model\Field\RichText richText($name, $label) Add RichText field to model
 * @method \W5n\Model\Field\Scholarity scholarity($name, $label, $defaultMessage = null) Add Scholarity field to model
 * @method \W5n\Model\Field\Serialized serialized($name) Add Serialized field to model
 * @method \W5n\Model\Field\Sex sex($name, $label, $defaultMessage = null) Add Sex field to model
 * @method \W5n\Model\Field\Slug slug($source_field, $updateable = false, $name = 'permalink', $label = 'Permalink') Add Slug field to model
 * @method \W5n\Model\Field\Text text($name, $label = null) Add Text field to model
 * @method \W5n\Model\Field\Time time($name, $label, $displayFormat = 'H:i', $saveFormat = 'H:i:s') Add Time field to model
 * @method \W5n\Model\Field\Timestamp timestamp($name, $onUpdate = false, $onCreate = true, $dateFormat = 'Y-m-d H:i:s') Add Timestamp field to model
 * @method \W5n\Model\Field\Uf uf($name, $label, $defaultMessage = null, $emptyMessage = null, $fullName = true) Add Uf field to model
 * @method \W5n\Model\Field\Upload upload($name, $label, $dir, $fileName = null, $allowedExts = [], $allowedMimes = [], $maxFileSize = null, $sizeField = null, $extField = null, $mimeField = null, $createDir = true) Add Upload field to model
 * @method \W5n\Model\Field\Url url($name, $label) Add Url field to model
 * @method \W5n\Model\Field\Video video($name, $label) Add Video field to model
 * @method \W5n\Model\Field\View view($name, $label, $view, $data = [], $errorField = null) Add View field to model
 * @method \W5n\Model\Field\YesNo yesNo($name, $label, $defaultMessage = null) Add YesNo field to model
 */

class Model extends Entity
{
    protected $table;
    protected $primaryKey = 'id';
    protected $sequence;
    protected $connection;

    protected $application;
    protected $data   = array();
    protected $fields = array();
    protected $defaultFieldSearchNamespace = 'W5n\\Model\\Field\\';
    protected $fieldSearchNamespaces       = array();
    protected $modelError = null;


    const OP_INSERT    = 'insert';
    const OP_UPDATE   = 'update';
    const OP_VALIDATE  = 'validate';

    const OP_POPULATE    = 'populate';
    const OP_DB_POPULATE = 'db_populate';

    public function __construct(Application $app = null)
    {
        parent::__construct();
        if ($app == null) {
            $app = Application::getDefault();
        }
        $this->application = $app;
        $this->init();
    }

    public function init() {}

    public function addField($field, $setModel = true)
    {
        if (!is_array($field)) {
            $field = array($field);
        }

        foreach ($field as $f) {
            if (!$f instanceof Field) {
                continue;
            }

            if ($setModel) {
                $f->setModel($this);
            }
            $this->fields[$f->getName()] = $f;
            if (isset($this->data[$f->getName()])) {
                $f->setValue($this->data[$f->getName()]);
                unset($this->data[$f->getName()]);
            }
        }

        return $this;
    }

    /**
     * @return Field
     */
    public function getField($fieldName)
    {
        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : null;
    }

    public function getFields()
    {
        return $this->fields;
    }

    function removeField($field) {
        if (is_null($field)) {
            return;
        }
        if (!$field instanceof Field) {
            $field = $this->getField($field);
        }
        $field->removeModel();
        unset($this->fields[$field->getName()]);
        return $field;
    }

    function hasField($fieldName) {
        return isset($this->fields[$fieldName]);
    }

    public function toArray($operation = null) {
        $data = $this->data;
        foreach ($this->getFields() as $f => $obj) {
            $data[$f] = $obj->getValue($operation);
        }
        return $data;
    }

    public function clearErrors()
    {
        foreach ($this->getFields() as $field) {
            $field->clearError();
        }
    }

    public function clearValues()
    {
        $this->data = array();
        foreach ($this->fields as $field) {
            $field->clearValue();
        }
    }

    public function hasErrors()
    {
        foreach ($this->fields as $field) {
            if ($field->hasError())
                return true;
        }
        return false;
    }

    public function hasError($fieldName) {
        return isset($this->fields[$fieldName]) && $this->fields[$fieldName]->hasError();
    }

    public function getError($fieldName)
    {
        return $this->hasField($fieldName) &&
               $this->getField($fieldName)->hasError()
               ? $this->getField($fieldName)->getError()
               : null;
    }

    public function getErrors()
    {
        $errors = array();
        foreach ($this->getFields() as $name => $f) {
            if ($f->hasError())
                $errors[$name] = $f->getError();
        }
        return $errors;
    }

    function __isset($fieldName)
    {
        return $this->hasField($fieldName) || isset($this->data[$fieldName]);
    }

    function __unset($fieldName)
    {
        if ($this->hasField($fieldName))
            $this->removeField($this->getField ($fieldName));
        elseif (isset($this->data[$fieldName]))
            unset($this->data[$fieldName]);
    }

    function __set($fieldName, $value) {
        if ($this->hasField($fieldName))
            $this->getField($fieldName)->setValue($value);
        else
            $this->data[$fieldName] = $value;
    }

    function __get($fieldName)
    {
        if ($this->hasField($fieldName))
            return $this->getField($fieldName)->getValue();
        elseif (isset($this->data[$fieldName]))
            return $this->data[$fieldName];
    }

    protected function getFullyQualifiedFieldClassNames($class)
    {
        $fullNames = array();
        foreach ($this->fieldSearchNamespaces as $namespace) {
            $fullNames[] = $namespace . $class;
        }
        $fullNames[] = $this->defaultFieldSearchNamespace . $class;
        return $fullNames;
    }

    public function addFilterSearchNamespace($namespace)
    {
        if (!substr($namespace, -1) == '\\') {
            $namespace .= '\\';
        }
        array_unshift($this->filterSearchNamespaces, $namespace);
        return $this;
    }

    public function removeFilterSearchNamespace($namespace)
    {
        if (!substr($namespace, -1) == '\\') {
            $namespace .= '\\';
        }
        $key = array_search($namespace, $this->filterSearchNamespaces);
        if ($key !== false) {
            unset($this->filterSearchNamespaces[$key]);
        }
        return $this;
    }

    public function __call($name, $arguments)
    {
        $className = ucfirst($name);
        $fullNames = $this->getFullyQualifiedFieldClassNames($className);
        foreach ($fullNames as $className) {
            if (!class_exists($className)) {
                continue;
            }
            try {
                $class    = new \ReflectionClass($className);
                $instance = $class->newInstanceArgs($arguments);
                $this->addField($instance);
                return $instance;
            } catch (\ReflectionException $ex) {
                throw new Exception($ex->getMessage());
            }
        }
        throw new Exception(sprintf('Field class "%s" not found.', $name));
    }

    protected function beforeSave($operation) {}
    protected function afterSave($success, $operation) {}

    protected function beforeFind($selectObj) {}
    protected function afterFind($resultObj) {}

    protected function beforePopulate(&$data) {}
    protected function afterPopulate($operation) {}

    protected function beforeValidate($operation) {}
    protected function afterValidate($success, $operation) {}

    protected function beforeDelete() {}
    protected function afterDelete($success) {}


    /**
     * @return \W5n\Application;
     */
    function getApplication()
    {
        return $this->application;
    }

    function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    public static function getTable()
    {
        return static::getDefaultPropertyValue('table');
    }

    public static function getPrimaryKey()
    {
        return static::getDefaultPropertyValue('primaryKey');
    }

    public static function getSequence()
    {
        return static::getDefaultPropertyValue('sequence');
    }

    public static function getConnectionName()
    {
        return static::getDefaultPropertyValue('connection');
    }

    /**
     *
     * @return \Doctrine\DBAL\Connection
     */
    public static function getConnection()
    {
        $connectionName = static::getConnectionName();
        $app            = \Application::getDefault();

        if (empty($connectionName)) {
            return $app->getDefaultDatabase();
        }

        return $app['db.' . $connectionName];
    }

    protected static function getDefaultPropertyValue($propertyName, $default = null)
    {
        static $cache = array();

        $class = get_called_class();

        if (isset($cache[$class])) {
            $params = $cache[$class];
        } else {
            $refClass      = new \ReflectionClass($class);
            $params        = $refClass->getDefaultProperties();
            $cache[$class] = $params;
        }

        return isset($params[$propertyName]) ? $params[$propertyName] : $default;
    }

    protected function getFieldsToSave($operation = NULL)
    {
        if (empty($this->fields)) {
            return $this->data;
        }
        $id     = $this->id();
        $fields = array();

        if (!empty($id)) {
            $fields[$this->getPrimaryKey()] = $id;
        }
        /*@var $f W_Model_Field*/
        foreach ($this->getFields() as $f) {
            if ($f->getModel() === $this && $f->isPersistent()) {
                $fields[$f->getName ()] = $f->getValue($operation);
            }
        }
        return $fields;
    }

    public function id($value = NULL) {
        if ($value === false) {
            $this->{$this->getPrimaryKey()} = null;
        } else if (is_null($value)) {
            return $this->{$this->getPrimaryKey()};
        } else {
           $this->{$this->getPrimaryKey()} = $value;
        }
    }

    public function save($validate = true, $forceInsert = false, $triggerBefore = true, $triggerAfter = true, $table = null)
    {
        return $this->doSave($validate, $forceInsert, $triggerBefore = true, $triggerAfter = true, $table);
    }

    protected function doSave($validate = true, $forceInsert = false, $triggerBefore = true, $triggerAfter = true, $table = null) {
        $save      = true;
        $operation = !empty($this->{$this->getPrimaryKey()})
                   ? self::OP_UPDATE
                   : self::OP_INSERT;

        if ($validate && !$this->doValidate($operation)) {
            $save = false;
        }

        $result    = $triggerBefore ? $this->triggerBeforeSave($operation) : null;

        $exception = null;
        try {
            if (!is_bool($result) || !$triggerBefore) {
                if ($save && !$this->hasErrors()) {
                    if ($forceInsert || $operation == self::OP_INSERT) {
                       $save = $this->doInsert($table);
                    } else {
                       $save = $this->doUpdate($table);
                    }
                } else {
                    $save = false;
                }
            } else  {
                $save = $result;
            }
        } catch (Exception $ex) {
            $exception = $ex;
            $save      = false;
        }

        if ($triggerAfter) {
            $afterSave = $this->triggerAfterSave($save, $operation);
        }
        if (!empty($ex)) {
            throw $ex;
        }
        return $save;
    }

    protected function doInsert($table = null)
    {
        if (is_null($table)) {
            $table = $this->getTable();
        }
        $db      = $this->getConnection();
        $pkValue = $this->{$this->getPrimaryKey()};
        $data    = $this->getFieldsToSave(self::OP_INSERT);
        $insert  = $db->insert($table, $data);



        $id = $db->lastInsertId($this->getSequence());

        if (empty($id)) {
            return false;
        }

        $this->id($id);

        return true;
    }

    protected function getLastInsertId()
    {
        return $this->getConnection()->lastInsertId($this->getSequence());
    }

    protected function doUpdate($table = null)
    {
        if (is_null($table)) {
            $table = $this->getTable();
        }
        $db = $this->getConnection();
        if (empty($this->{$this->getPrimaryKey()})) {
            return false;
        }

        $pkValue = $this->{$this->getPrimaryKey()};
        $data    = $this->getFieldsToSave(self::OP_UPDATE);
        $db->update(
            $table,
            $data,
            array($this->getPrimaryKey() => $pkValue)
        );
        return true;
    }

    function delete($table = null)
    {
        return $this->doDelete($table);
    }

    protected function doDelete($table = null)
    {
        if (is_null($table)) {
            $table = $this->getTable();
        }

        $db = $this->getConnection();
        $id = $this->id();

        if (empty($id)) {
            return false;
        }

        $result = $this->triggerBeforeDelete();

        if (!is_bool($result)) {
            $result = (bool)$db->delete(
                $table,
                array($this->getPrimaryKey() => $id)
            );
            if ($result) {
                $this->id(false);
            }
        }
        $this->triggerAfterDelete($result);
        return (bool)$result;
    }

    public function validate()
    {
        return $this->doValidate(self::OP_VALIDATE);
    }

    protected function doValidate($operation)
    {
        $this->clearErrors();
        $return = $this->triggerBeforeValidate($operation);
        if (!is_bool($return)) {
            $hasError = false;
            foreach ($this->getFields() as $field) {
                if (!$field->validate($operation) && !$hasError)
                    $hasError = true;
            }
            $return = !$hasError;
        }
        $this->triggerAfterValidate($return, $operation);
        return $return;
    }


    protected function triggerBeforeDelete()
    {
        $result = $this->beforeDelete();
        foreach ($this->getFields() as $f) {
            if (is_object($f)  && $f->getModel() == $this)
                $f->beforeDelete();
        }
        return $result;
    }

    protected function triggerAfterDelete($success)
    {
        $result = $this->afterDelete($success);
        foreach ($this->getFields() as $f) {
            if (is_object($f)  && $f->getModel() == $this)
                $f->afterDelete($success);
        }
        return $result;
    }

    protected function triggerBeforeSave($operation)
    {
        $result = $this->beforeSave($operation);

        foreach ($this->getFields() as $f) {
            if (is_object($f) && $f->getModel() == $this)
                $f->beforeSave($operation);
        }

        return $result;
    }

    protected function triggerAfterSave($success, $operation)
    {
        $result = $this->afterSave($success, $operation);
        foreach ($this->getFields() as $f) {
            if (is_object($f) && $f->getModel() == $this)
                $f->afterSave($success, $operation);
        }
        return $result;
    }

    protected function triggerBeforeValidate($operation)
    {
        $result = $this->beforeValidate($operation);
        foreach ($this->getFields() as $f) {
            if (is_object($f) && $f->getModel() == $this)
                $f->beforeValidate($operation);
        }
        return $result;
    }

    protected function triggerAfterValidate($success, $operation)
    {
        $result = $this->afterValidate($success, $operation);
        foreach ($this->getFields() as $f) {
            if (is_object($f) && $f->getModel() == $this)
                $f->afterValidate($success, $operation);
        }
        return $result;
    }

    public static function findAll(
        $conditions = array(), $modifyQueryCallback = null,
        $orderBy = null, $direction = null, $limit = null, $offset = null
    ) {
        $query = static::buildFindQuery(
            $conditions, $modifyQueryCallback, false, $orderBy,
            $direction, $limit, $offset
        );

        return $query->execute()->fetchAll();
    }

    public static function inflate(array $data)
    {
        $model = new static();
        $model->populateFromArray($data, true, self::OP_DB_POPULATE);
        return $model;
    }

    public static function inflateAll(array $data)
    {
        return array_map(function ($d) {
            return static::inflate($d);
        }, $data);

    }

    public static function findAllPaginated(
        $page = 1, $perPage = 10, &$totalPages = null, &$totalRows = null,
        $conditions = array(), $modifyQueryCallback = null,
        $orderBy = null, $direction = null
    ) {
        $query = static::buildFindQuery(
            $conditions, $modifyQueryCallback, true, $orderBy, $direction
        );
        $query->select('COUNT(*) as total_rows');

        $countResult = $query->execute()->fetch();

        $totalRows  = $countResult['total_rows'];

        if ($totalRows == 0) {
            return array();
        }

        $totalPages = ceil($totalRows / $perPage);


        if ($page > $totalPages) {
            return array();
        }

        $offset = ($page - 1) * $perPage;

        $query = static::buildFindQuery(
            $conditions, $modifyQueryCallback, false, $orderBy, $direction
        );

        $query->setFirstResult($offset);
        $query->setMaxResults($perPage);

        return $query->execute()->fetchAll();
    }

    protected static function buildFindQuery(
        $conditions = array(), $modifyQueryCallback = null, $isCountQuery = false,
        $orderBy = null, $direction = null, $limit = null, $offset = null
    ) {
        $query      = static::getFindQueryBuilder();
        $paramCount = 0;

        if (!empty($conditions)) {
            if (!is_array($conditions)) {
                $conditions = array($conditions);
            }
            foreach ($conditions as $condition => $parameters) {

                if (is_numeric($condition)) {
                    $query->andWhere($parameters);
                    continue;
                }
                $query->andWhere($condition);
                if (!is_array($parameters)) {
                    $query->setParameter(($paramCount++), $parameters);
                } else {
                   foreach ($parameters as $key => $value) {
                       $query->setParameter($key, $value);
                   }
                }
            }
        }

        if (!empty($orderBy)) {
            $query->orderBy($orderBy, $direction);
        }

        if (!empty($limit)) {
            $query->setMaxResults($limit);
        }

        if (!empty($offset)) {
            $query->setFirstResult($offset);
        }

        if (is_callable($modifyQueryCallback)) {
            call_user_func($modifyQueryCallback, $query, $isCountQuery);
        }

        return $query;
    }

    public static function find($conditions = array(), $modifyQueryCallback = null, $createCallback = null)
    {
        $query = static::buildFindQuery($conditions, $modifyQueryCallback);

        $result = $query->execute()->fetch();

        if (empty($result)) {
            return null;
        }
        if (is_callable($createCallback)) {
            $model = call_user_func($createCallback, $result);
        } else {
            $model = new static();
        }


        $model->populateFromArray($result, true, self::OP_DB_POPULATE);
        return $model;
    }
    /**
     *
     * @param int $id
     * @param callback $modifyQueryCallback
     * @param callback $createCallback
     * @return Model
     */
    public static function findById($id, $modifyQueryCallback = null, $createCallback = null)
    {
        return static::findBy(static::getTable() . '.' . static::getPrimaryKey(), $id, $modifyQueryCallback, $createCallback);
    }

    public static function findBy($column, $value, $modifyQueryCallback = null, $createCallback = null)
    {
        return static::find(array($column . '=:value' => array(':value' => $value)), $modifyQueryCallback, $createCallback);
    }

    public function populateFromArray($data, $clear = false, $operation = self::OP_POPULATE) {
        $this->doPopulateFromArray($data, $clear, $operation);
    }

    protected function doPopulateFromArray($data, $clear, $operation) {
        if ($clear) {
            $this->clearValues();
        }
        $this->beforePopulate($data);
        foreach ($this->fields as $f) {
            if (is_object($f)) {
                $f->beforeModelPopulate($data, $operation);
            }
        }

        if (empty($data)) {
            return;
        }

        foreach ($data as $var => $value) {
            if ($this->hasField($var)) {
                $field = $this->getField($var);
                $field->beforeSetValue($value, $operation);
                $field->setValue($value, FALSE);
                $field->afterSetValue($operation);
            } else {
                $this->data[$var] = $value;
            }
        }
        $this->afterPopulate($operation);
        foreach ($this->fields as $f) {
            if (is_object($f)) {
                $f->afterModelPopulate($operation);
            }
        }
    }

    /**
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getFindQueryBuilder()
    {
        $db = static::getConnection();
        $queryBuilder = $db
            ->createQueryBuilder()
            ->select('*')
            ->from(static::getTable());

        static::modifyFindQueryBuilder($queryBuilder);
        return $queryBuilder;
    }

    public static function modifyFindQueryBuilder(\Doctrine\DBAL\Query\QueryBuilder $queryBuilder)
    {

    }

    public function setModelError($error)
    {
        $this->modelError = $error;
        return $this;
    }

    public function hasModelError()
    {
        return !empty($this->modelError);
    }

    public function getModelError()
    {
        return $this->modelError;
    }

    public function clearModelError()
    {
        $this->modelError = null;
        return $this;
    }
}
