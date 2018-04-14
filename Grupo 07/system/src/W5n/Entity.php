<?php
namespace W5n;

use Symfony\Component\HttpFoundation\ParameterBag;

class Entity extends \Symfony\Component\EventDispatcher\EventDispatcher
{
    /**
     * @var ParameterBag
     */
    protected $options;
    
    public function __construct()
    {}
    
    /**
     * @return ParameterBag
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            $this->options = new ParameterBag();
        }
        return $this->options;
    }
    
    public function setOption($key, $value)
    {
        $this->getOptions()->set($key, $value);
        return $this;
    }
    
    public function getOption($key, $default = null, $deep = false)
    {
        return $this->getOptions()->get($key, $default, $deep);
    }
    
    public function hasOption($key)
    {
        return $this->getOptions()->has($key);
    }
    
    public function setOptions(array $options)
    {
        $this->getOptions()->add($options);
    }
    
}
