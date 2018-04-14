<?php
namespace W5n\Html;

class Tag
{
    protected $name        = array();
    protected $attributes  = array();
    protected $empty       = false;
    protected $textTag     = false;
    protected $childs      = array();
    protected $prefixes    = array();
    protected $suffixes    = array();
    protected $prettyPrint = true;

    public function __construct($name = 'div', array $attrs = array())
    {
        $this->setName($name);
        $this->setAttributes($attrs);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function isEmpty()
    {
        return $this->empty;
    }

    public function isTextTag()
    {
        return $this->textTag;
    }

    public function getChilds()
    {
        return $this->childs;
    }

    public function appendChild(Tag $child)
    {
        $this->childs[] = $child;
        return $this;
    }

    /**
     * @return \W5n\Html\Tag
     */
    public function appendTag($name =  'div', array $attrs = array(), $returnChild = true)
    {
        $tag = new Tag($name, $attrs);
        $this->appendChild($tag);
        if ($returnChild) {
            return $tag;
        }
        return $this;
    }

    public function appendText($content, $returnChild = false)
    {
        $tag = $this->text($content);
        $this->appendChild($tag);
        if ($returnChild) {
            return $tag;
        }

        return $this;
    }

    public function text($content)
    {
        return new Text($content);
    }

    public function addClass($class)
    {
        $currentClasses = $this->getAttr('class');
        if (!empty($currentClasses)) {
            $classes = explode(' ', $currentClasses);
            if (array_search($class, $classes) !== false) {
                return $this;
            }
            $classes[]      = $class;
            $class = implode(' ', $classes);
        }

        $this->setAttr('class', $class);
        return $this;
    }

    public function removeClass($class)
    {
        $currentClasses = $this->getAttr('class');
        if (empty($currentClasses))
            return $this;

        $classes = explode(' ', $currentClasses);
        $key     = array_search($class, $classes);
        if ($key === false)
            return $this;

        unset($classes[$key]);
        if (empty($classes))
            $this->removeAttr('class');
        else
            $this->setAttr('class', implode(' ', $classes));
        return $this;
    }

    public function hasClass($class)
    {
        $currentClasses = $this->getAttr('class');
        if (empty($currentClasses))
            return false;

        $classes = explode(' ', $currentClasses);
        return array_search($class, $classes) !== false;
    }

    public function prependChild(Tag $child)
    {
        array_unshift($this->childs, $child);
        return $this;
    }

    public function prependText($content, $returnChild = false)
    {
        $tag = new Text($content);
        $this->prependChild($tag);
        if ($returnChild) {
            return $tag;
        }

        return $this;
    }

    public function before($before)
    {
        if (!($before instanceof Tag)) {
            $before = $this->text($before);
        }
        $this->prefixes[] = $before;
        return $this;
    }

    public function after($after)
    {
        if (!($after instanceof Tag)) {
            $after = $this->text($after);
        }
        $this->suffixes[] = $after;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function mergeAttributes(array $attributes)
    {
        if (is_array($attributes)) {
            foreach ($attributes as $name => $value) {
                if (strtolower($name) == 'class') {
                    $this->addClass($value);
                } else {
                    $this->attributes[$name] = $value;
                }
            }
        }
        return $this;
    }

    public function getAttr($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    public function setAttr($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    public function removeAttr($name)
    {
        unset($this->attributes[$name]);
        return $this;
    }
    public function clearAttributes()
    {
        $this->attributes = array();
        return $this;
    }

    public function setEmpty($isEmpty)
    {
        $this->empty = $isEmpty;
        return $this;
    }

    public function getAttrsString($prefixSpace = true)
    {
        return static::arrayToAttrsString($this->attributes, $prefixSpace);
    }

    public static function arrayToAttrsString(array $attributes, $prefixSpace = true)
    {
        if (empty($attributes)) {
            return '';
        }
        $attrs = '';
        foreach ($attributes as $name => $value) {
            $attrs .= sprintf(' %s="%s"', $name, $value);
        }
        return $prefixSpace ? $attrs : rtrim($attrs);
    }

    public function getOpenTagString()
    {
        $template = $this->isEmpty() ? '<%s%s />' : '<%s%s>';
        return sprintf($template, $this->getName(), $this->getAttrsString());
    }

    public function open()
    {
        return $this->getOpenTagString();
    }

    public function close()
    {
        return $this->getCloseTagString();
    }

    public function getCloseTagString()
    {
        if ($this->isEmpty())
            return '';
        return sprintf('</%s>', $this->getName());
    }

    public function render($deepIndex = 0)
    {
        $prefix = $this->prettyPrint ? PHP_EOL . str_repeat("\t", $deepIndex) : '';

        if ($this->isEmpty()) {
            return $this->renderPrefixes($deepIndex) . $prefix . $this->getOpenTagString() . $this->renderSuffixes($deepIndex);
        }

        $str = $prefix . $this->getOpenTagString();
        foreach ($this->childs as $c) {
            $str .= $c->render($deepIndex + 1);
        }
        $str .= $prefix . $this->getCloseTagString();
        return $this->renderPrefixes($deepIndex) . $str . $this->renderSuffixes($deepIndex);
    }

    protected function renderPrefixes($deepIndex)
    {
        $str = '';
        foreach ($this->prefixes as $p) {
            $str .= $p->render($deepIndex);
        }
        return $str;
    }

    protected function renderSuffixes($deepIndex)
    {
        $str = '';
        foreach ($this->suffixes as $p) {
            $str .= $p->render($deepIndex);
        }
        return $str;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __get($name)
    {
        isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function isPrettyPrint()
    {
        return $this->prettyPrint;
    }

    public function setPrettyPrint($prettyPrint)
    {
        $this->prettyPrint = $prettyPrint;
        return $this;
    }

    /**
     * @return \W5n\Html\Tag
     */
    public function clearContent()
    {
        $this->childs = array();
        return $this;
    }


}
