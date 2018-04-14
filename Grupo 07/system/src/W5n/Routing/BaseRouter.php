<?php
namespace W5n\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Generator\UrlGenerator;

class BaseRouter
{
    /**
     * Generates an absolute URL, e.g. "http://example.com/dir/file".
     */
    const ABSOLUTE_URL = 0;

    /**
     * Generates an absolute path, e.g. "/dir/file".
     */
    const ABSOLUTE_PATH = 1;

    /**
     * Generates a relative path based on the current request path, e.g. "../parent-file".
     *
     * @see UrlGenerator::getRelativePath()
     */
    const RELATIVE_PATH = 2;

    /**
     * Generates a network path, e.g. "//example.com/dir/file".
     * Such reference reuses the current scheme but specifies the host.
     */
    const NETWORK_PATH = 3;

    protected static $routeCollection = null;

    public static function add($name, Route $route)
    {
        static::getRouteCollection()->add($name, $route);
        return $route;
    }

    public static function set($name, $path, array $defaults = array(), array $requirements = array(), array $options = array(), $host = '', $schemes = array(), $methods = array(), $condition = '')
    {
        $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
        return static::add($name, $route);
    }

    public static function addCollection(RouteCollection $collection)
    {
        return static::getRouteCollection()->addCollection($collection);
    }

    public static function get($name)
    {
        return static::getRouteCollection()->get($name);
    }

    public static function remove($name)
    {
        return static::getRouteCollection()->remove($name);
    }

    public static function getRoutes()
    {
        return static::getRouteCollection()->all();
    }

    /**
     *
     * @return \W5n\Routing\RouteCollection
     */
    public static function getRouteCollection()
    {
        if (is_null(self::$routeCollection)) {
            self::$routeCollection = new \W5n\Routing\RouteCollection();
        }

        return self::$routeCollection;
    }

    public static function match(Request $request)
    {
        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);

        $matcher = new UrlMatcher(static::getRouteCollection(), $requestContext);

        return $matcher->matchRequest($request);
    }

    public static function generate(Request $request, $name, array $params = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);
        $generator      = new UrlGenerator(
            static::getRouteCollection(), $requestContext
        );

        return $generator->generate($name, $params, $referenceType);
    }
}
