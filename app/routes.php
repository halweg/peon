<?php

use App\Http\Controllers\Users\RegisterUserController;
use Framework\Routing\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Products\ListProductsController;
use App\Http\Controllers\Products\ShowProductController;
use App\Http\Controllers\Services\ShowServiceController;
use App\Http\Controllers\Users\ShowRegisterFormController;

return function(Router $router) {
    $router->add(
        'GET', '/',
        [new HomeController($router), 'handle'],
    );

    $router->add(
        'GET', '/products/views/{product}',
        [new ShowProductController($router), 'handle'],
    )->name('view-product');

    $router->add(
        'GET', '/productss/{page?}',
        [new ListProductsController($router), 'handle'],
    )->name('list-products');

    $router->add(
        'GET', '/services/view/{service?}',
        [new ShowServiceController($router), 'handle'],
    )->name('show-service');

    $router->add(
        'GET', '/register',
        [new ShowRegisterFormController($router), 'handle'],
    )->name('show-register-form');

    $router->add(
        'POST', '/register',
        [new RegisterUserController($router), 'handle'],
    )->name('register-user');

    $router->add(
        'GET', '/old-home',
        fn() => $router->redirect('/'),
    );

    $router->add(
        'GET', '/has-server-error',
        fn() => throw new Exception(),
    );

    $router->add(
        'GET', '/has-validation-error',
        fn() => $router->dispatchNotAllowed()
    );

    $router->errorHandler(
        404, fn() => 'page not found!'
    );

    $router->add(
        'GET', '/products/view/{product}',
        function () use ($router) {
            $parameters = $router->current()->parameters();
            return view('products/view', [
                'product' => $parameters['product'],
                'scary' => '<script>alert("boo!")</script>',
            ]);
        },
    );

    $router->add(
        'GET', '/products/{page?}',
        function () use ($router) {
            $parameters = $router->current()->parameters();
            $parameters['page'] ??= 1;

            $next = $router->route(
                'product-list', ['page' => $parameters['page'] + 1]
            );

            return view('products/list', [
                'next' => $next,
            ]);
        },
    )->name('product-list');

    $router->add(
        'GET', '/products/{page?}',
        [new ListProductsController($router), 'handle'],
    )->name('list-products');

    $router->add(
        'GET', '/services/view/{service?}',
        function () use ($router) {
            $parameters = $router->current()->parameters();

            if (empty($parameters['service'])) {
                return 'all services';
            }

            return "service is {$parameters['service']}";
        },
    );
};
