<?php
namespace Dashboard;

use Application;
use Symfony\Component\HttpFoundation\Request;
use W5n\Module;
use W5n\Routing\RouteCollection;

class DashboardModule extends Module
{
    public function initRoutes(RouteCollection $routes)
    {
    }

    public function init(Application $app, Request $req, RouteCollection $routes)
    {
    }
}
