<?php

namespace App\Http\Controllers;

use Framework\Routing\Router;
class ListProductsController
{
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    public function handle()
    {
        $parameters = $this->router->current()->parameters();
        $parameters['page'] ??= 1;
        $next = $this->router->route(
            'list-products', [
                'page' => $parameters['page'] + 1,
            ]
        );

        return view('products/list', [
        'parameters' => $parameters,
        'next' => $next,
        ]);
    }
}