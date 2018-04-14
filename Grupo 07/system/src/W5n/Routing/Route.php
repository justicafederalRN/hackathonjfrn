<?php
namespace W5n\Routing;

use Symfony\Component\Routing\Route as BaseRoute;

class Route extends BaseRoute
{
    private $callbackCondition = null;
    private $priority          = 0;

    function getCallbackCondition()
    {
        return $this->callbackCondition;
    }

    function setCallbackCondition(callable $callbackCondition)
    {
        $this->callbackCondition = $callbackCondition;
        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }
}
