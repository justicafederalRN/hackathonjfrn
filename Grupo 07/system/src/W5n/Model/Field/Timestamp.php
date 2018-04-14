<?php
namespace W5n\Model\Field;


class Timestamp extends Field
{

    protected $onCreate;
    protected $onUpdate;
    protected $dateFormat;

    function __construct($name, $onUpdate = false, $onCreate = true, $dateFormat = 'Y-m-d H:i:s')
    {
        $this->onCreate   = $onCreate;
        $this->onUpdate   = $onUpdate;
        $this->dateFormat = $dateFormat;
        parent::__construct($name, $name);
    }

    public function beforeSave($operation)
    {
        if (!$this->onCreate && !$this->onUpdate) {
            return;
        }

        $value = date($this->dateFormat);
        if ($operation == \W5n\Model\Model::OP_INSERT && $this->onCreate) {
            $this->setValue($value);
        }

        if ($operation == \W5n\Model\Model::OP_UPDATE && $this->onUpdate) {
            $this->setValue($value);
        }
    }

    public function getDisplayValue()
    {
        return date('d/m/Y à\s H:i:s', strtotime($this->value));
    }

    public function toHtml(Field $field)
    {
        return \W5n\Html\HtmlBuilder::tag('span');
    }

}
