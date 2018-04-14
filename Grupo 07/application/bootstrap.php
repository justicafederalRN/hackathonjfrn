<?php
$loader = require_once VENDOR_PATH . 'autoload.php';
require_once SYSTEM_PATH . 'src/W5n/Application.php';
require_once SYSTEM_PATH . 'src/W5n/Exception.php';

require_once APPLICATION_PATH . 'src/Application.php';

$env = isset($_SERVER['ENV']) ? $_SERVER['ENV'] :  \Application::ENV_DEVELOPMENT;

$app = new \Application($env);

if ($env == \Application::ENV_DEVELOPMENT) {
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', true);
    $app['displayErrors'] = true;
}

try {
    $app->run();
} catch (\Exception $ex) {
    if ($env == \Application::ENV_DEVELOPMENT) {
        throw $ex;
    }
}

