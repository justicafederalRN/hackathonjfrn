<?php
namespace W5n\Html;

class Engine {
    
    protected $variables      = array();
    protected $variableRegex  = '#\[\[(?P<variable>.*?)\]\]#';
    protected $maxParseTimes  = 10;
    
    protected static $defaultEngine = null;
    
    /**
     * 
     * @return \W5n\Html\Engine
     */
    public static function getDefaultEngine()
    {
        if (empty(self::$defaultEngine)) {
            self::$defaultEngine = new Engine();
        }
        return self::$defaultEngine;
    }
    
    public static function setDefaultEngine(Engine $e)
    {
        self::$defaultEngine = $e;
        return $this;
    }

    public function addVariable($name, $label, $handler, array $defaultOptions = array())
    {
        $this->variables[$name] = array(
            'name'            => $name,
            'label'           => $label,
            'handler'         => $handler,
            'defaultOptions'  => $defaultOptions
        );
        return $this;
    }
    
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
    }
    
    public function getVariableInfo($name)
    {
        if (!$this->hasVariable($name)) {
            return null;
        }
        return $this->variables[$name];
    }
    
    public function resolveVariable($name, array $options = array())
    {
        $info = $this->getVariableInfo($name);
        
        if (empty($info)) {
            return null;
        }
        
        $handler        = $info['handler'];
        $defaultOptions = $info['defaultOptions'];
        
        $options = array_merge($defaultOptions, $options);

        $result = call_user_func($handler, $options, $this);
        
        return $result;
    }
    
    public function parse($template)
    {
        return $this->parseRecursive($template, $this->maxParseTimes);
    }
    
    protected function parseRecursive($template, $triesLeft)
    {
        $engine = $this;
        
        $content = preg_replace_callback($this->variableRegex, function ($match) use ($engine) {
            $def = $engine->parseVariableDefinition($match['variable']);
            return $engine->resolveVariable($def['name'], $def['options']);
        }, $template, -1, $count);
        
        if ($count == 0 || $triesLeft == 1) {
            if ($triesLeft == 1) {
                return preg_replace($this->variableRegex, '', $content);
            }
            return $content;
        }
        
        return $this->parseRecursive($content, $triesLeft-1);
    }
    
    protected function parseVariableDefinition($str)
    {
        $result  = explode(';', $str);
        $options = array();
        $name    = trim($result[0]);

        if (!empty($result[1])) {
            parse_str(trim($result[1]), $options);
        }
        
        return array(
            'name'    => $name,
            'options' => $options
        );
    }
    
    public function extractVariables($template, $includeValues = false)
    {
        preg_match_all($this->variableRegex, $template, $matches);
        $variables = array();
        if (empty($matches['variable'])) {
            return array();
        }
        
        foreach ($matches['variable'] as $v) {
            if (isset($variables[$v])) {
                continue;
            }
            $variable    = $this->parseVariableDefinition($v);
            if ($includeValues) {
                $variable['value'] = $this->resolveVariable($variable['name'], $variable['options']);
            }
            $variables[$v] = $variable;
        }
        
        return $variables;
    }
    
    public function getVariableLabel($name)
    {
        return $this->getVariableProperty($name, 'label');
    }
    
    public function getVariableDefaultOptions($name)
    {
        return $this->getVariableProperty($name, 'defaultOptions');
    }
    
    public function getVariableHandler($name)
    {
        return $this->getVariableProperty($name, 'handler');
    }
    
    protected function getVariableProperty($name, $key)
    {
        $info = $this->getVariableInfo($name);
        
        if (empty($info)) {
            return null;
        }
        
        return isset($info[$key]) ? $info[$key] : null;
    }
    
    public function parseToPhp($template, $varName = 'data')
    {
        $engine = $this;
        return preg_replace_callback($this->variableRegex, function ($match) use ($varName) {
            return '<?=$' . $varName . '[\'' . str_replace("'", "\\'", $match['variable']) . '\']?>';
        }, $template);
    }
    
    public function getVariables()
    {
        return $this->variables;
    }
    
    public function removeVariable($name)
    {
        unset($this->variables[$name]);
        return $this;
    }
}