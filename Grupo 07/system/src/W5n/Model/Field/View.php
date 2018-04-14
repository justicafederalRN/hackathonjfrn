<?php
namespace W5n\Model\Field;

use W5n\Model\Field\Field;

class View extends Field
{

    function __construct($name, $label, $view, array $data = array(), $errorField = null)
    {
        parent::__construct($name, $label);
        $this->setPersistent(false);
        $this->setOption('view', $view);
        $this->setOption('viewData', $data);
        $this->setOption('errorField', $errorField);
    }

    public function afterValidate($success, $operation)
    {
        $errorField = $this->getOption('errorField');
        if (!$success && !empty($errorField)) {
            $error = $this->getModel()->getField($errorField)->getError();
            if (!empty($error)) {
                $this->setError($error);
            }
        }
    }

    public function toHtml(Field $field)
    {
        $data = $this->getOption('viewData', array());
        $data['field']     = $this;
        $data['model']     = $this->getModel();
        $data['value']     = $this->getValue();
        $content = new \W5n\Templating\View(
            $this->getModel()->getApplication(),
            $this->getOption('view'),
            $data
        );
        $content = $content->render();

        $container = \W5n\Html\HtmlBuilder::tag('div');
        $container->addClass('view-container input-view');
        $container->appendText($content);
        return $container;
    }
}
