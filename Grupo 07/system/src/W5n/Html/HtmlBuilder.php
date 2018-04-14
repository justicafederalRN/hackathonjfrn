<?php
namespace W5n\Html;

class HtmlBuilder 
{
    /**
     * @return \W5n\Html\Tag
     */
    public static function tag($name, $attributes = array())
    {
        return new Tag($name, $attributes);
    }
    
    /**
     * @return \W5n\Html\Text
     */
    public static function text($content)
    {
        return new Text($content);
    }
    
    /**
     * @return \W5n\Html\Tag
     */
    public static function input($name = null, $value = null, $type = 'text')
    {
        $attrs = array();
        if (!empty($name)) {
            $attrs['name'] = $name;
        }
        if (!empty($type)) {
            $attrs['type'] = $type;
        }
        if (strlen($value) > 0) {
            $attrs['value'] = $value;
        }
        
        $tag = self::tag('input', $attrs);
        $tag->setEmpty(true);
        return $tag;
    }
    
    /**
     * @return \W5n\Html\Tag
     */
    public static function select($name, array $options, $value = null)
    {
        if (empty($value) && strlen($value) == 0) {
            $value = array();
        }
        
        if (!is_array($value)) {
            $value = array($value);
        }
        
        
        $tag = self::tag('select', array('name' => $name));
        
        foreach ($options as $v => $l) {
            $option = $tag->appendTag('option', array('value' => $v));
            if (in_array($v, $value)) {
                $option->setAttr('selected', 'selected');
            }
            $option->appendText($l);
        }
        return $tag;
    }
    
    /**
     * @return \W5n\Html\Tag
     */
    public static function script($src = null)
    {
        $tag = self::tag('script')->setAttr('type', 'text/javascript');
        if (!empty($src))
            $tag->setAttr ('src', $src);
        return $tag;
    }
    
    /**
     * @return \W5n\Html\Tag
     */
    public static function img($src, $alt = null, array $attrs = array())
    {
        $attrs['src'] = $src;
        if (!empty($alt))
            $attrs['alt'] = $alt;
        
        
        
        
        return self::tag('img', $attrs);
    }
}
