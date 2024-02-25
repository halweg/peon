<?php

require_once __DIR__ . '/../vendor/autoload.php';
define("ROOT_PATH", dirname(__DIR__));
$router = new Framework\Routing\Router();
$routes = require_once __DIR__ . '/../app/routes.php';
$routes($router);
print $router->dispatch();


