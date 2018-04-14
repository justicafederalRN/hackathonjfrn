<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app['loggger_handler'] = function() {
    return new StreamHandler(
        APPLICATION_PATH . 'logs' . DS . date('Y-m-d') . '.log',
        Logger::DEBUG
    );
};

$app['logger'] = function ($app) {
    $logger = new Logger('app');
    $logger->pushHandler($app['log_handler']);
    return $logger;
};
