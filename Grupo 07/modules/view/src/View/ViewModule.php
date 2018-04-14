<?php
namespace View;

use W5n\Templating\View;

class ViewModule extends \W5n\Module
{
    public function initServices(\Application $app)
    {
        $app['view'] = $app->factory(function ($app) {
            return new View($app);
        });
    }
}