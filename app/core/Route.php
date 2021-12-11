<?php

class Route
{
    public static function get($path, $callback)
    {
        if (!self::isRequestMethod('get')) {
            return;
        }
        $route = '/'.($_GET['route'] ?? '');
        if ($route == $path && is_callable($callback)) {
            (new $callback[0])->{$callback[1]}();
            exit;
        }
    }
    
    public static function post($path, $callback)
    {
        if (!self::isRequestMethod('post')) {
            return;
        }
        $route = '/'.($_GET['route'] ?? '');
        if ($route == $path && is_callable($callback)) {
            (new $callback[0])->{$callback[1]}();
            exit;
        }
    }
    
    public static function redirect($path)
    {
        header("Location: $path");
        exit;
    }
    
    public static function isRequestMethod($type)
    {
        return strcasecmp($_SERVER['REQUEST_METHOD'], $type) == 0;
    }
}