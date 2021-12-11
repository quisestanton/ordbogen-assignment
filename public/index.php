<?php

session_start();

require __DIR__.'/../vendor/autoload.php';

foreach (glob(__DIR__.'/../app/core/*.php') as $file) {
    require $file;
}
foreach (glob(__DIR__.'/../app/support/*.php') as $file) {
    require $file;
}
foreach (glob(__DIR__.'/../app/models/*.php') as $file) {
    require $file;
}
foreach (glob(__DIR__.'/../app/controllers/*.php') as $file) {
    require $file;
}
require __DIR__.'/../routes/web.php';
