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
        $connection = $factory->connect([
            'type' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'demo',
            'username' => 'root',
            'password' => '123456',
        ]);
        $product = $connection
            ->query()
            ->select()
            ->from('demo')
            ->first();
        var_dump($product);
        return view('home', [
            'number' => 42,
            'featured' => $product,
        ]);
    }
}