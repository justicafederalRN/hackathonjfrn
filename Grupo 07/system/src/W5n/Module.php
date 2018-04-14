<?php
namespace W5n;

use Symfony\Component\HttpFoundation\Request;
use W5n\Routing\RouteCollection;

class Module
{
    public function __construct()
    {}

    function initRoutes(RouteCollection $routes)
    {}

    function initServices(\Application $app)
    {}

    function init(\Application $app, Request $req, RouteCollection $routes)
    {}

}
