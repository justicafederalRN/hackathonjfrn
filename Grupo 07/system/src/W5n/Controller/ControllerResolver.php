<?php
namespace W5n\Controller;

use Symfony\Component\HttpKernel\Controller\ControllerResolver
    as BaseControllerResolver;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver extends BaseControllerResolver
{
    protected $application = null;
    protected $request     = null;

    public function __construct(\W5n\Application $app, Request $request)
    {
        $this->application = $app;
        $this->request     = $request;
    }

    /**
     * {@inheritdoc}
     *
     * This method looks for a '_controller' request attribute that represents
     * the controller name (a string like ClassName::MethodName).
     */
    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('controller')) {
            if (null !== $this->logger) {
                $this->logger->warning('Unable to look for the controller as the "controller" parameter is missing.');
            }

            return false;
        }

        if (is_array($controller)) {
            return array($this->instantiateController($controller[0]), $controller[1]);
        } else if (($action = $request->attributes->get('action')) !== null) {
            $action = preg_replace_callback('#-([a-zA-Z])#', function ($a) {
                return mb_strtoupper($a[1]);
            }, $action);
            $controller .= '::' . $action . 'Action';
        }

        if (is_object($controller)) {
            if (method_exists($controller, '__invoke')) {
                return $controller;
            }

            throw new \InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', get_class($controller), $request->getPathInfo()));
        }

        if (false === strpos($controller, ':')) {
            if (method_exists($controller, '__invoke')) {
                return $this->instantiateController($controller);
            } elseif (function_exists($controller)) {
                return $controller;
            }
        }

        $callable = $this->createController($controller);

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('The controller for URI "%s" is not callable. %s', $request->getPathInfo(), $this->getControllerError($callable)));
        }

        return $callable;
    }

    protected function instantiateController($class)
    {
        $refClass          = new \ReflectionClass($class);
        $constructor       = $refClass->getConstructor();
        if (empty($constructor)) {
            return $refClass->newInstanceArgs();
        } else {
            $constructorParams = $constructor->getParameters();
            $parameters        = array();
            foreach ($constructorParams as $p) {
                $pClass = $p->getClass();
                if ($pClass !== null) {
                    if ($pClass->isInstance($this->request)) {
                        $parameters[] = $this->request;
                        continue;
                    } else if ($pClass->isInstance($this->application)) {
                        $parameters[] = $this->application;
                        continue;
                    }
                }
                if ($this->request->attributes->has($p->getName())) {
                    $parameters[] = $request->attributes->get($p->getName());
                } elseif ($p->isOptional()) {
                    $parameters[] = $p->getDefaultValue();
                } else {
                    throw Exception(
                        sprintf(
                            'You must provide a value to "%s" in "%s"\'s constructor.',
                            $p->getName(),
                            $refClass->getName()
                        )
                    );
                }

            }
            return $refClass->newInstanceArgs($parameters);
        }

    }

    private function getControllerError($callable)
    {
        if (is_string($callable)) {
            if (false !== strpos($callable, '::')) {
                $callable = explode('::', $callable);
            }

            if (class_exists($callable) && !method_exists($callable, '__invoke')) {
                return sprintf('Class "%s" does not have a method "__invoke".', $callable);
            }

            if (!function_exists($callable)) {
                return sprintf('Function "%s" does not exist.', $callable);
            }
        }

        if (!is_array($callable)) {
            return sprintf('Invalid type for controller given, expected string or array, got "%s".', gettype($callable));
        }

        if (2 !== count($callable)) {
            return sprintf('Invalid format for controller, expected array(controller, method) or controller::method.');
        }

        list($controller, $method) = $callable;

        if (is_string($controller) && !class_exists($controller)) {
            return sprintf('Class "%s" does not exist.', $controller);
        }

        $className = is_object($controller) ? get_class($controller) : $controller;

        if (method_exists($controller, $method)) {
            return sprintf('Method "%s" on class "%s" should be public and non-abstract.', $method, $className);
        }

        $collection = get_class_methods($controller);

        $alternatives = array();

        foreach ($collection as $item) {
            $lev = levenshtein($method, $item);

            if ($lev <= strlen($method) / 3 || false !== strpos($item, $method)) {
                $alternatives[] = $item;
            }
        }

        asort($alternatives);

        $message = sprintf('Expected method "%s" on class "%s"', $method, $className);

        if (count($alternatives) > 0) {
            $message .= sprintf(', did you mean "%s"?', implode('", "', $alternatives));
        } else {
            $message .= sprintf('. Available methods: "%s".', implode('", "', $collection));
        }

        return $message;
    }

}
