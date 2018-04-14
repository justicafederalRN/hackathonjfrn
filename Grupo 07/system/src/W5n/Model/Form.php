<?php
namespace W5n\Model;

use Symfony\Component\HttpFoundation\Request;
use W5n\Html\HtmlBuilder;
use W5n\Exception;

class Form extends \W5n\Html\Tag
{

    /**
     * @var W_Model
     */
    protected $model;
    protected $formKeyFieldName = '__form_key';


    protected static $defaultErrorPrefix = '<div class="input-error-msg">';
    protected static $defaultErrorSuffix = '</div>';


    public function __construct(Model $model, $action, $method = 'post', $multipart = true, $attrs = array())
    {
        parent::__construct('form', $attrs);
        $this->model = $model;
        $this->setAttr('action', $action);
        $this->setAttr('method', $method);

        if ($multipart) {
            $this->setAttr('enctype', 'multipart/form-data');
        }

        $this->mergeAttributes($attrs);
    }

    /**
     * @return W_Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function modelLabel($name, $for = null, $attrs = array())
    {
        $mandatory = false;
        $fieldName = $name;
        if ($this->getModel()->hasField($name)) {
            $field     = $this->getModel()->getField($name);
            $label     = $this->getModel()->getField($name)->getLabel();
            if ($label === false) {
                return;
            }
            if (!empty($label)) {
                $name = $label;
            }
        }

        $label = \W5n\Html\HtmlBuilder::tag('label');
        $label->appendChild(\W5n\Html\HtmlBuilder::text($name));
        $label->for = empty($for) ? 'field_' . $fieldName : $for;
        $label->mergeAttributes($attrs);

        if ($mandatory)
            $label->addClass('required');
        return $label;
    }

    public function modelCheck($name)
    {
        return $this->modelLabel($name)->prependText(' ')->prependChild($this->modelField($name));
    }


    public function modelError($name, $before = '<small class="text-danger">', $after = '</small>')
    {
        if (!$this->hasError($name))  {
            return null;
        }
        if (empty($before)) {
            $before = self::$defaultErrorPrefix;
        }

        if (empty($after)) {
            $after = self::$defaultErrorSuffix;
        }
        return $before.$this->getModel()->getField($name)->getError().$after;
    }

    public function hasInfo($name)
    {
        if ($this->getModel()->hasField($name)) {
            $field = $this->getModel()->getField($name);
            $info  = $field->getInfo();
            return !empty($info);
        }
        return false;
    }

    public function modelInfo($name, $before = '<small class="help-block">', $after = '</small>')
    {
        if (!$this->hasInfo($name)) {
            return null;
        }
        return $before.$this->getModel()->getField($name)->getInfo().$after;
    }

    public function hasError($name)
    {
        if (!$this->getModel()->hasField($name)) {
            return false;
        }
        return $this->getModel()->getField($name)->hasError();
    }

    /**
     * Create a new html input radio element
     * @return W_Html_Form_InputRadio
     */
    function radio($name, $value, array $attrs = array())
    {
        $radio = parent::radio($name, $value, $attrs);
        if ($this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            if ($value == $model_value) {
                $radio->setAttr('checked', 'checked');
            }
        }
        return $radio;
    }

    /**
     * Create a new html input button element
     * @return W_Html_Form_InputButton
     */
    function button($name, $value, array $attrs = array())
    {
        $button = parent::button($name, $value, $attrs);
        if (is_null($value) && $this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $button->setAttr('value', $model_value);
        }
        return $button;
    }

    /**
     * Create a new html input reset element
     * @return W_Html_Form_InputReset
     */
    function reset($name, $value, array $attrs = array())
    {
        $reset = parent::reset($name, $value, $attrs);
        if (is_null($value) && $this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $reset->setAttr('value', $model_value);
        }
        return $reset;
    }

    /**
     * Create a new html input submit element
     * @return W_Html_Form_InputSubmit
     */
    function submit($name, $value, array $attrs = array())
    {
        $submit = parent::submit($name, $value, $attrs);
        if (is_null($value) && $this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $submit->setAttr('value', $model_value);
        }
        return $submit;
    }

    /**
     * Create a new html input text element
     * @return W_Html_Form_InputText
     */
    function text($name, $default_value = NULL, array $attrs = array())
    {
        $text = parent::text($name, $default_value, $attrs);
        if ($this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $text->setAttr('value', $model_value);
        }
        return $text;
    }

    /**
     * Create a new html input checkbox element
     * @return W_Html_Form_InputCheckbox
     */
    function checkbox($name, $value, $unchecked_value = null, array $attrs = array())
    {
        $checkbox = parent::checkbox($name, $value, $unchecked_value, $attrs);
        if ($this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            if ($value == $model_value) {
                $checkbox->setAttr('checked', 'checked');
            }

        }
        return $checkbox;
    }


    /**
     * Create a new html input hidden element
     * @return W_Html_Form_InputHidden
     */
    function hidden($name, $default_value, array $attrs = array())
    {
        $hidden = HtmlBuilder::input($name, $default_value, 'hidden');
        $hidden->mergeAttributes($attrs);
        if ($this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $hidden->setAttr('value', $model_value);
        }
        return $hidden;
    }

    /**
     * Create a new html select element
     * @return W_Html_Form_Select
     */
    function select($name, array $options = array(), $attrs = array())
    {
        $select = parent::select($name, $options, $attrs);
        if ($this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $select->setAttr('value', $model_value);
        }
        return $select;
    }

    /**
     * Create a new html textarea element
     * @return W_Html_Form_Textarea
     */
    function textarea($name, array $rows = null, $cols = null, $attrs = array())
    {
        $textarea = parent::textarea($name, $rows, $cols, $attrs);
        if ($this->getModel()->hasField($name)) {
            $model_value = $this->getModel()->getField($name)->get_value();
            $textarea->appendText($model_value);
        }
        return $textarea;
    }

    /**
     *
     */
    function modelField($name, $options = array())
    {
        if (!$this->getModel()->hasField($name)) {
            throw new Exception (sprintf('Field '.$name.' not found in model object (%s)', get_class($this->getModel())));
        }
        /*@var $field Field_Html*/
        $field = $this->getModel()->getField($name);
        if (!$field instanceof \W5n\Model\Field\Field) {
            throw new \W5n\Exception ('Field '.$name.' must be child of \W5n\Model\Field\Field class');
        }
        $f = $field->toHtml($field);

        if ($this->hasError($name)) {
            $f->addClass('is-invalid');
        }

        if (is_object($f)) {
            $f->setAttr('id', 'field_' . $name);
        }

        return $f;
    }

    /**
     *
     */
    function modelValue($name, $operation = null)
    {
        if (!$this->getModel()->hasField($name)) {
            throw new Exception ('Field '.$name.' not found in model object');
        }
        /*@var $field Field_Html*/
        $field = $this->getModel()->getField($name);
        if (!$field instanceof \W5n\Model\Field\Field) {
            throw new \W5n\Exception ('Field '.$name.' must be child of \W5n\Model\Field\Field class');
        }

        return $field->getValue($operation);
    }

    function modelDisplayValue($name)
    {
        if (!$this->getModel()->hasField($name)) {
            throw new Exception ('Field '.$name.' not found in model object');
        }
        /*@var $field Field_Html*/
        $field = $this->getModel()->getField($name);
        if (!$field instanceof \W5n\Model\Field\Field) {
            throw new \W5n\Exception ('Field '.$name.' must be child of \W5n\Model\Field\Field class');
        }

        return $field->getDisplayValue();
    }

    public function getFormKey()
    {
        return sha1(get_class($this->model) . serialize($this->getAttr('method')));
    }

    public function close()
    {
        $close  = parent::close();
        $hidden = strval($this->hidden($this->formKeyFieldName, $this->getFormKey()));
        return $hidden . $close;
    }

    public function isSubmitted(Request $req, $populateIfSubmited = true)
    {
        if (!$req->isMethod($this->getAttr('method'))) {
            return false;
        }


        $key    = $req->get($this->formKeyFieldName);
        $result = !empty($key) && $key == $this->getFormKey();
        if ($result&& $populateIfSubmited) {
            $this->model->populateFromArray($req->request->all());
        }
        return $result;
    }

    static function getDefaultErrorPrefix()
    {
        return self::$defaultErrorPrefix;
    }

    static function getDefaultErrorSuffix()
    {
        return self::$defaultErrorSuffix;
    }

    static function setDefaultErrorPrefix($defaultErrorPrefix)
    {
        self::$defaultErrorPrefix = $defaultErrorPrefix;
    }

    static function setDefaultErrorSuffix($defaultErrorSuffix)
    {
        self::$defaultErrorSuffix = $defaultErrorSuffix;
    }


}
