<?php

namespace Db;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class DatabaseModule extends \W5n\Module
{

    public function initServices(\Application $app)
    {
        $app['db_config'] = function() use ($app) {
            return new Configuration();
        };


        $app['db'] = function() use ($app) {
            $config = $app->loadConfig('database');
            $conn   = DriverManager::getConnection($config, $app['db_config']);
            /*$conn->executeQuery('set names utf8');
            $conn->executeQuery("set sql_mode=''");*/

            return $conn;
        };
    }
}
