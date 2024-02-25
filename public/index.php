<?php

require_once __DIR__ . '/../vendor/autoload.php';
session_start();
define("ROOT_PATH", dirname(__DIR__));
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$router = new Framework\Routing\Router();
$routes = require_once __DIR__ . '/../app/routes.php';
$routes($router);
print $router->dispatch();