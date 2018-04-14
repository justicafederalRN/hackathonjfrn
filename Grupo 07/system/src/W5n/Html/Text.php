<?php
namespace W5n\Html;

class Text extends Tag
{
    protected $content;
    
    public function __construct($content)
    {
        $this->content = $content;
        $this->setPrettyPrint(false);
    }
    
    public function render($deepIndex = 0)
    {
        $prefix = $this->prettyPrint ? PHP_EOL . str_repeat("\t", $deepIndex) : '';
        return $prefix . $this->content;
    }
    
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

}