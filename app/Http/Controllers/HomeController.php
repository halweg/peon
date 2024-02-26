<?php
namespace App\Http\Controllers;
use Framework\Routing\Router;

use Framework\Database\Factory;
use Framework\Database\Connection\MysqlConnection;

class HomeController
{
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    public function handle()
    {
        $factory = new Factory();
        $factory->addConnector('mysql', function($config) {
            return new MysqlConnection($config);
        });
        $config = require __DIR__ . '/../../../config/database.php';
        $connection = $factory->connect($config[$config['default']]);
        $product = $connection
            ->query()
            ->select()
            ->from('orders')
            ->first();
        var_dump($product);
        return view('home', [
            'number' => 42,
            'featured' => $product,
        ]);
    }
}