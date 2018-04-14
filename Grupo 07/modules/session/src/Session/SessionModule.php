<?php
namespace Session;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionModule extends \W5n\Module
{
    public function initServices(\Application $app)
    {
        $app['session_storage'] = function ($app) {
            return null;
        };

        $app['session_attribute_bag'] = function ($app) {
            return null;
        };

        $app['session_flash_bag'] = function ($app) {
            return null;
        };

        $app['session'] = function($app) {
            $session = new Session(
                $app['session_storage'], $app['session_attribute_bag'], $app['session_flash_bag']
            );
            $session->start();
            return $session;
        };
    }
}
