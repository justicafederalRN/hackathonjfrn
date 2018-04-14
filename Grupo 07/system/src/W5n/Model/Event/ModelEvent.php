<?php
namespace W5n\Model\Event;

use W5n\Model\Model;

class ModelEvent extends \Symfony\Component\EventDispatcher\Event
{
    protected $model;
    protected $handled;
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    /**
     * @return \W5n\Model\Model
     */
    public function getModel()
    {
        return $this->model;
    }
    
    public function handled($boolResult, $stopPropagation = true)
    {
        $this->handled = (bool)$boolResult;
        if ($stopPropagation) {
            $this->stopPropagation();
        }
        return $this;
    }
    
    public function isHandled()
    {
        return is_bool($this->handled);
    }
    
    public function getHandledResult()
    {
        return $this->handled;
    }
    
}

