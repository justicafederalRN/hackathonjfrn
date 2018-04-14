<?php
namespace Ajax;

use \Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\JsonResponse;

class AjaxManager
{
    private static $handlers = [];

    public static function addHandler($handlerId, callable $handler)
    {
        self::$handlers[$handlerId] = $handler;
    }

    public static function removeHandler($handlerId)
    {
        unset(self::$handlers[$handlerId]);
    }

    public static function hasHandler($handlerId)
    {
        return isset(self::$handlers[$handlerId]);
    }

    public static function callHandler($handlerId, Request $request, JsonResponse $jsonResponse, Application $application)
    {
        if (!self::hasHandler($handlerId)) {
            return;
        }

        $handler = self::$handlers[$handlerId];

        return call_user_func($handler, $request, $jsonResponse, $application);
    }
}
