<?php
namespace W5n\Model\Field;

use W5n\Exception;
use W5n\Model\Model;
use W5n\Model\Field\Validation\Validation;
use W5n\Entity;

/**
 * @method $this callback($callback, $errorMessage) Add Callback validation to this field
 * @method $this cnpj() Add Cnpj validation to this field
 * @method $this conditional($validation, $errorMessage, $callback) Add Conditional validation to this field
 * @method $this cpf() Add Cpf validation to this field
 * @method $this date($format = 'Y-m-d') Add Date validation to this field
 * @method $this dateGreaterThan($otherDateField, $allowEquals = true) Add DateGreaterThan validation to this field
 * @method $this dateLesserThan($otherDateField, $allowEquals = true) Add DateLesserThan validation to this field
 * @method $this datetime($format = 'Y-m-d') Add Datetime validation to this field
 * @method $this dun14() Add Dun14 validation to this field
 * @method $this ean13() Add Ean13 validation to this field
 * @method $this email() Add Email validation to this field
 * @method $this exists($fkTable, $fkField, $db = null) Add Exists validation to this field
 * @method $this greaterThan($otherDateField, $allowEquals = true) Add GreaterThan validation to this field
 * @method $this imageExactHeight($height, $errorMessage = null) Add ImageExactHeight validation to this field
 * @method $this imageExactWidth($width, $errorMessage = null) Add ImageExactWidth validation to this field
 * @method $this imageMaxDimension($dimension) Add ImageMaxDimension validation to this field
 * @method $this imageMaxHeight($height, $errorMessage = null) Add ImageMaxHeight validation to this field
 * @method $this imageMaxSize($size, $errorMessage = null) Add ImageMaxSize validation to this field
 * @method $this imageMaxWidth($width, $errorMessage = null) Add ImageMaxWidth validation to this field
 * @method $this imageMinHeight($height, $errorMessage = null) Add ImageMinHeight validation to this field
 * @method $this imageMinWidth($width, $errorMessage = null) Add ImageMinWidth validation to this field
 * @method $this inSet($set) Add InSet validation to this field
 * @method $this integer() Add Integer validation to this field
 * @method $this lessThan($otherDateField, $allowEquals = true) Add LessThan validation to this field
 * @method $this mandatory($callback = null) Add Mandatory validation to this field
 * @method $this mandatoryOnInsert() Add MandatoryOnInsert validation to this field
 * @method $this mandatoryOnUpdate() Add MandatoryOnUpdate validation to this field
 * @method $this match($otherPasswordFieldName, $ignoreOnEmpty = true) Add Match validation to this field
 * @method $this max($value, $allowEquals = true) Add Max validation to this field
 * @method $this maxCount($value, $allowEquals, $errorMessage) Add MaxCount validation to this field
 * @method $this maxLength($maxLength) Add MaxLength validation to this field
 * @method $this min($value, $allowEquals = true) Add Min validation to this field
 * @method $this minCount($value, $allowEquals, $errorMessage) Add MinCount validation to this field
 * @method $this minLength($maxLength) Add MinLength validation to this field
 * @method $this multiline($validation, $errorMessage = null, $lineSeparator = "\n") Add Multiline validation to this field
 * @method $this number() Add Number validation to this field
 * @method $this regex($regex) Add Regex validation to this field
 * @method $this toggleEnabled($fields = [], $otherSelectors = []) Add ToggleEnabled validation to this field
 * @method $this toggleVisible($fields = [], $otherSelectors = []) Add ToggleVisible validation to this field
 * @method $this unique($fields = null, $modifyQueryCallback = null, $modifyFieldsCallback = null) Add Unique validation to this field
 * @method $this url() Add Url validation to this field
 * @method $this validateImage() Add ValidateImage validation to this field
 * @method $this validation() Add Validation validation to this field
 */
class Field extends Entity implements Renderer
{

    protected $value    = null;
    protected $name     = null;
    protected $label    = null;
    protected $gender   = self::GENDER_MALE;
    protected $info     = null;
    protected $model    = null;
    protected $error    = null;
    protected $renderer = null;

    protected $persistent  = true;
    protected $validations = array();

    protected $defaultValidatorSearchNamespace = 'W5n\\Model\\Field\\Validation\\';
    protected $validatorSearchNamespaces       = array();

    const GENDER_MALE   = 'm';
    const GENDER_FEMALE = 'f';

    public function __construct($name, $label = null)
    {
        parent::__construct();

        if (empty($name)) {
            throw new Exception('The field\'s name cannot be empty');
        }

        $this->setName($name);
        $this->setLabel($label);
        $this->setRenderer($this);
        $this->init();
    }

    public function init() {}

    public function beforeSave($operation) {}
    public function afterSave($success, $operation) {}

    public function beforeSetValue(&$value, $operation) {}
    public function afterSetValue($operation) {}

    public function beforeModelPopulate($data, $operation) {}
    public function afterModelPopulate($operation) {}

    public function beforeGetValue(&$value, $operation) {}

    public function beforeValidate($operation) {}
    public function afterValidate($success, $operation) {}

    public function beforeDelete() {}
    public function afterDelete($success) {}

    public function isPersistent()
    {
        return $this->persistent;
    }

    public function setPersistent($bool)
    {
        $this->persistent = (bool) $bool;
        return $this;
    }

    public function persistent()
    {
        return $this->setPersistent(true);
    }

    public function transient()
    {
        return $this->setPersistent(false);
    }

    public function getValue($operation = NULL)
    {
        $value = $this->value;
        $this->beforeGetValue($value, $operation);
        return $value;
    }

    public function getDisplayValue()
    {
        return $this->getValue();
    }

    public function setValue($value, $trigger = TRUE)
    {
        if ($trigger)
            $this->beforeSetValue($value, NULL);
        $this->value = $value;
        if ($trigger)
            $this->afterSetValue(NULL);

        return $this;
    }


    public function clearValue()
    {
        $this->value = null;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function male()
    {
        return $this->setGender(self::GENDER_MALE);
    }

    public function female()
    {
        return $this->setGender(self::GENDER_FEMALE);
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }
    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setModel(Model $model)
    {
        if ($this->model instanceof Model)
            $this->model->removeField($this);
        $this->model = $model;
        return $this;
    }

    public function removeModel()
    {
        $this->model = NULL;
        return $this;
    }

    public function getValidations()
    {
        return array_values($this->validations);
    }

    public function clearValidations()
    {
        $this->validations = array();
    }

    public function addValidation(Validation $validation, $options = array(), $message = NULL)
    {
        $this->validations[] = array(
            'validation' => $validation,
            'options'    => $options,
            'message'    => $message
        );

        return $this;
    }

    public function hasValidation(Validation $validation)
    {
        foreach ($this->validations as $v) {
            if ($v['validation'] === $validation) {
                return true;
            }
        }
        return false;
    }

    public function validate($operation = null)
    {
        return $this->doValidate($operation);
    }

    protected function doValidate($operation)
    {
        $this->clearError();
        $result = $this->beforeValidate($operation);
        if (!is_bool($result)) {
            $hasError = false;
            $value    = $this->getValue();
            $isEmpty  = !is_array($value) ? strlen($value) == 0 : empty($value);
            foreach ($this->validations as $v) {
                $validation = $v['validation'];
                if ($isEmpty && $validation->skipIfEmpty())
                    continue;

                $options    = $v['options'];
                if (!$validation->validate($value, $this, $operation)) {
                    $errorMessage = empty($v['message'])
                                   ? $validation->getErrorMessage($this, $operation)
                                   : $v['message'];
                    $this->setError($errorMessage);
                    $hasError = true;
                    break;
                }
            }
            $result = !$hasError;
        }
        $this->afterValidate($result, $operation);
        return $result;
    }

    public function removeValidation(Validation $validation)
    {
        if (!$this->hasValidation($validation))
            return false;

        foreach ($this->validations as $idx => $v) {
            if ($v['validation'] === $validation) {
                unset($this->validations[$idx]);
                return true;
            }
        }
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function clearError()
    {
        $this->error = null;
    }

    public function hasError()
    {
        return $this->error !== null;
    }

    public function isMandatory()
    {
        if (empty($this->validations))
            return false;
        foreach ($this->validations as $v) {
            if ($v['validation'] instanceof Validation\Mandatory)
                return true;
        }
        return false;
    }

    public function toHtml(Field $field)
    {
        throw new Exception('"toHtml" method not implemented');
    }

    public function applyValidationHtmlModifications(\W5n\Html\Tag $html)
    {
        foreach ($this->validations as $v) {
            $validation = $v['validation'];
            $validation->beforeRenderField($html, $this);
        }
        $beforeRender = $this->getOption('beforeRender');

        if (!empty($beforeRender) && is_callable($beforeRender)) {
            call_user_func($beforeRender, $html, $this);
        }

        return $this;
    }

    public function __invoke($value = null)
    {
        if ($value == null) {
            return $this->value;
        } else {
            $this->setValue($value);
            return $this;
        }
    }

    public function __toString()
    {
        return strval($this->value);
    }

    public function addValidatorSearchNamespace($namespace)
    {
        if (!substr($namespace, -1, 1) != '\\') {
            $namespace .= '\\';
        }
        array_unshift($this->validatorSearchNamespaces, $namespace);
    }

    public function removeValidatorSearchNamespace($namespace)
    {
        if (!substr($namespace, -1, 1) != '\\') {
            $namespace .= '\\';
        }
        $key = array_search($namespace, $this->validatorSearchNamespaces);
        if ($key !== false)
            unset($this->validatorSearchNamespaces[$key]);
    }

    public function clearValidatorSearchNamespaces()
    {
        $this->validatorSearchNamespaces = array();
    }

    public function getValidatorSearchNamespaces()
    {
        return $this->validatorSearchNamespaces;
    }

    protected function getFullyQualifiedClassNames($class)
    {
        $ns        = $this->validatorSearchNamespaces;
        $dns       = $this->defaultValidatorSearchNamespace;
        $fullNames = array();
        foreach ($ns as $n) {
            $fullNames[] = $n . $class;
        }
        $fullNames[] = $dns . $class;
        return $fullNames;
    }

    public function __call($name, $arguments)
    {
        $className      = ucfirst($name);
        $fullClassNames = $this->getFullyQualifiedClassNames($className);

        foreach ($fullClassNames as $className) {
            if (class_exists($className)) {
                try {
                    $class    = new \ReflectionClass($className);
                    $instance = $class->newInstanceArgs($arguments);
                    return $this->addValidation($instance);
                } catch (\ReflectionException $ex) {
                    throw new Exception($ex->getMessage());
                }
            }
        }
        throw new Exception(
            sprintf(
                'Validation class "%s" not found.', ucfirst($name)
            )
        );
    }

    function getRenderer()
    {
        return $this->renderer;
    }

    function setRenderer(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

}
