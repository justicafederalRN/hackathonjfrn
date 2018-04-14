<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');
require_once realpath(__DIR__ . '/../system/src/W5n/Application.php');
require_once realpath(__DIR__ . '/../system/src/W5n/Exception.php');
require_once realpath(__DIR__ . '/src/Application.php');


try {
    define('APPLICATION_PATH', __DIR__ . '/');
    define('SYSTEM_PATH', dirname(__DIR__) . '/system/');
    define('MODULES_PATH', dirname(__DIR__) . '/modules/');
    $app = new \Application('test');
    $app['catchExceptions'] = false;
    $app->run(false);
    return $app;
} catch (\Exception $ex) {
    die("[ERROR] " . $ex->getMessage());
}
