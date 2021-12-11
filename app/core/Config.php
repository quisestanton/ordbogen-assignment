<?php

class Config
{
    public static function getValue($path)
    {
        $keys = explode('.', $path);
        $config = require __DIR__.'/../../config/'.array_shift($keys).'.php';
        foreach ($keys as $key) {
            $config = &$config[$key];
        }
        return $config;
    }
}