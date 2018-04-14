<?php
namespace Ajax;

use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class AjaxModule extends \W5n\Module
{
    public function initRoutes(RouteCollection $routes)
    {
        $routes->set(
            'ajax.handler',
            'ajax/{handler}',
            [
                'controller' => '\\Ajax\\Controller\\AjaxController',
                'action'     => 'handle'
            ]
        )->setPriority(-100);
    }
}
