<?php

require_once __DIR__ . '/../vendor/autoload.php';
$router = new Framework\Routing\Router();

$routes = require_once __DIR__ . '/../app/routes.php';
require_once __DIR__ . '/../framework/helpers.php';
$routes($router);
print $router->dispatch();


