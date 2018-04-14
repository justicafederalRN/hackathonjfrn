<?php
$vendorDir      = '../vendor';
$applicationDir = '../application';
$modulesDir     = '../modules';
$systemDir      = '../system';
$assetsDir      = 'assets';


define('DS', DIRECTORY_SEPARATOR);
define('VENDOR_PATH', realpath($vendorDir) . DS);
define('APPLICATION_PATH', realpath($applicationDir) . DS);
define('CACHE_PATH', APPLICATION_PATH . 'cache' . DS);
define('MODULES_PATH', realpath($modulesDir) . DS);
define('SYSTEM_PATH', realpath($systemDir) . DS);
define('ASSETS_PATH', realpath($assetsDir) . DS);
define('PUBLIC_PATH', realpath('.') . DS);


unset($vendorDir);
unset($applicationDir);
unset($modulesDir);
unset($systemDir);
unset($assetsDir);

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

require_once APPLICATION_PATH . 'bootstrap.php';
