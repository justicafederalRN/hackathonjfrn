<?php
namespace W5n\Routing;

use Symfony\Component\Routing\RouteCollection as BaseRouteCollection;

class RouteCollection extends BaseRouteCollection
{

    /**
     * @return \W5n\Routing\Route
     */
    public function set($name, $path, array $defaults = array(), array $requirements = array(), array $options = array(), $host = '', $schemes = array(), $methods = array(), $condition = '')
    {
        $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
        $this->add($name, $route);
        return $route;
    }


    public function createSortedCollection()
    {
        $routes = $this->all();

        uasort(
            $routes,
            function ($first, $second) {
                //sort desc
                $firstP  = $first->getPriority();
                $secondP = $second->getPriority();

                if ($firstP > $secondP) {
                    return -1;
                }

                return $firstP > $secondP ? -1 : 1;
            }
        );

        $collection = new RouteCollection();

        foreach ($routes as $name => $route) {
            $collection->add($name, $route);
        }

        return $collection;
    }
}
