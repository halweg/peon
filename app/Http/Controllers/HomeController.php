<?php
namespace App\Http\Controllers;
use Framework\Routing\Router;

class HomeController
{
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    public function handle(): string
    {
        $parameters = $this->router->current()->parameters();
        $parameters['page'] ??= 1;
        $next = $this->router->route(
            'list-products', ['page' => $parameters['page'] + 1]
        );
        return view('products/list', [
            'parameters' => $parameters,
            'next' => $next,
        ]);
    }
}