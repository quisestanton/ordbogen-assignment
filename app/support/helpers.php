<?php

function env($key, $default = null)
{
    $path = __DIR__.'/../../.env';
    $value = '';
    if (file_exists($path)) {
        $value = parse_ini_file($path)[$key] ?? '';
    }
    return $value ?: $default;
}