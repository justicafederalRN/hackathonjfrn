<?php
namespace W5n\Templating;

use W5n\Exception;

class BaseView implements \ArrayAccess
{
    protected static $globals     = array();
    protected $variables          = array();
    protected $parent             = null;
    protected $helpers            = array();
    protected $content            = null;
    protected $application        = null;
    protected $viewFile           = null;
    protected $parameters         = array();
    protected $blocks             = array();
    protected $blocksStack        = array();
    protected $defaultRouteParams = array();

    public function __construct(\Application $app, $viewFile = null, array $params = array())
    {
        $this->application = $app;
        $this->viewFile    = $viewFile;
        $this->parameters  = $params;
    }

    public function setFile($filename)
    {
        $this->viewFile = $filename;
        return $this;
    }

    public function setParameters(array $params)
    {
        $this->parameters = $params;
        return $this;
    }

    public function getFile()
    {
        return $this->viewFile;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public static function addGlobal($var, $value)
    {
        self::$globals[$var] = $value;
    }

    public function set($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }

    public function get($name, $default = null)
    {
        return isset($this->variables[$name]) ? $this->variables[$name] : $default;
    }

    public function addHelper(ViewHelperInterface $helper)
    {
        $this->variables[$helper->getName()] = $helper;
        return $this;
    }

    public function removeHelper(ViewHelperInterface $helper)
    {
        if (($key = array_search($helper, $this->variables)) !== false) {
            unset($this->variables[$key]);
        }
        return $this;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    public function __unset($name)
    {
        unset($this->variables[$name]);
    }

    public function offsetExists($offset)
    {
        return isset($this->helpers[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->helpers[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (!($value instanceof ViewHelperInterface)) {
            throw new Exception('Array access is only for view helpers.');
        }
        return $this->helpers[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->helpers[$offset]);
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function extend($parentViewFile)
    {
        if (!is_null($parentViewFile)) {
            $viewPath = $this->application->findFile($parentViewFile . '.php', 'views');

            if ($viewPath === false) {
                throw new Exception(sprintf('View file not found "%s".', $parentViewFile));
            }
            $this->parent = $parentViewFile;
        } else {
            $this->parent = null;
        }
        return $this;
    }

    public function render($viewFile = null, array $params = array())
    {
        if (empty($viewFile)) {
            $viewFile = $this->viewFile;
        }

        if (!empty($params)) {
            $params = array_merge($this->parameters, $params);
        } else {
            $params = $this->parameters;
        }

        if (empty($viewFile)) {
            throw new Exception('A view file must be set to render.');
        }

        $viewPath = $this->application->findFile($viewFile . '.php', 'views');
        if ($viewPath === false) {
            throw new Exception(sprintf('View file not found "%s".', $viewFile));
        }

        $allParams = self::$globals;
        $allParams = array_merge($allParams, $this->variables);
        $allParams = array_merge($allParams, $params);

        $content   = $this->doRenderFile($viewPath, $allParams);

        if (!empty($this->parent)) {
            $oldContent = $this->content;
            try {
                $parent = new View($this->getApplication(), $params);
                $parent->blocks  = $this->blocks;
                $parent->helpers = $this->helpers;
                $parent->setContent($content);

                return $parent->render($this->parent, $this->variables);
            } catch (\Exception $ex) {
                $this->setContent($oldContent);
                throw $ex;
            }
            $this->setContent($oldContent);
        }

        return $content;
    }

    public function renderPartial($viewFile, $extraData = [])
    {
        $variables = array_merge($this->variables, $extraData);
        $partial   = new self($this->application, $viewFile, $variables);
        return $partial->render();
    }

    public function setBlock($block, $value, $append = false)
    {
        if ($append && !empty($this->blocks[$block])) {
            $this->blocks[$block] .= $value;
        } else {
            $this->blocks[$block] = $value;
        }
        return $this;
    }

    public function renderBlock($name, $default = '')
    {
        return isset($this->blocks[$name]) ? $this->blocks[$name] : $default;
    }

    public function hasBlock($blockName)
    {
        return !empty($this->blocks[$blockName]);
    }

    public function start($name)
    {
        array_push($this->blocksStack, $name);
        ob_start();
        return $this;
    }

    public function end()
    {
        $content = ob_get_clean();
        $block   = array_pop($this->blocksStack);
        if (!empty($block)) {
            if (!isset($this->blocks[$block])) {
                $this->blocks[$block] = $content;
            } else {
                $this->blocks[$block] .= $content;
            }
        }
        return $this;
    }

    protected function doRenderFile($file, $params)
    {
        ob_start();
        extract($params);
        foreach ($params as $var => $value) {
            $this->set($var, $value);
        }
        require $file;
        return ob_get_clean();
    }

    /**
     *
     * @return \Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }

    public function url($path)
    {
        return $this->getApplication()->getMasterRequest()->getUriForPath($path);
    }

    public function routeUrl($route, $params = [], $referenceType = \W5n\Routing\Router::RELATIVE_PATH)
    {
        $params = array_merge($this->defaultRouteParams, $params);

        return \W5n\Routing\Router::generate(
            $this->getApplication()->getMasterRequest(),
            $route,
            $params,
            $referenceType
        );
    }

    public function currentUrl()
    {
        return $this->getApplication()->getMasterRequest()->getPathInfo();
    }

    public function renderFlashes()
    {
        $application = \Application::getDefault();
        $ui          = $application['admin.ui_manager'];
        $session     = $application['session'];

        return $ui->renderFlashMessages($session->getFlashBag()->all());
    }

    function getDefaultRouteParams()
    {
        return $this->defaultRouteParams;
    }

    function setDefaultRouteParams(array $defaultRouteParams)
    {
        $this->defaultRouteParams = $defaultRouteParams;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addDefaultRouteParam($key, $value)
    {
        $this->defaultRouteParams[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasDefaultRouteParam($key)
    {
        return isset($this->defaultRouteParams[$key]);
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeDefaultRouteParam($key)
    {
        unset($this->defaultRouteParams[$key]);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearDefaultRouteParams()
    {
        $this->defaultRouteParams = [];
        return $this;
    }
}
