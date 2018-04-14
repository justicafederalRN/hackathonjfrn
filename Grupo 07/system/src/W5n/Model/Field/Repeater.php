<?php
namespace W5n\Model\Field;

use W5n\Html\Tag;
use View;

class Repeater extends HasMany
{
    
    const AT_LEAST_ONE  = 1;
    const EMPTY_ALLOWED = 0;
    
    function __construct(
        $name, $label, $referenceColumn, 
        $instantiateFunction, $itemLayout,
        $mode = self::AT_LEAST_ONE,
        $emptyMessage = null,
        $modifyFindQueryCallback = null
    ) {
        parent::__construct($name, $referenceColumn, $instantiateFunction, $modifyFindQueryCallback);
        $this->setLabel($label);
        $this->setOption('mode', $mode);
        $this->setOption('itemLayout', $itemLayout);
    }
    
    public function toHtml(Field $field)
    {
        
        $view = View::factory(
            'fields/repeater', 
            [
                'model'       => $this->getModel(), 
                'application' => $this->getModel()->getApplication(),
                'field'       => $this,
                'mode'        => $this->getOption('mode'),
                'itemLayout'  => $this->getOption('itemLayout')
            ]
        );
        $input = \W5n\Html\HtmlBuilder::tag('div');
        $input->appendText($view->render());
        return $input;
    }
}


