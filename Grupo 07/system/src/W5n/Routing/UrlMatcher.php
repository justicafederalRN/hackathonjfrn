<?php
namespace W5n\Routing;

use Symfony\Component\Routing\Matcher\UrlMatcher as BaseMatcher;
use Symfony\Component\Routing\RequestContext;

class UrlMatcher extends BaseMatcher
{
    public function __construct(RouteCollection $routes, RequestContext $context)
    {
        $routes = $routes->createSortedCollection();
        parent::__construct($routes, $context);
    }

    protected function handleRouteRequirements($pathinfo, $name, \Symfony\Component\Routing\Route $route)
    {
        if ($route instanceof Route) {
            $callbackCondition = $route->getCallbackCondition();

            if (!empty($callbackCondition)) {
                if (!call_user_func($callbackCondition, $this->request, $this->context, $route, $name)) {
                    return array(self::REQUIREMENT_MISMATCH, null);
                }
            }
        }

        return parent::handleRouteRequirements($pathinfo, $name, $route);
    }


}
