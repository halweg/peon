<?php

namespace Framework\Routing;

use Exception;
use Throwable;

class Router
{
    /**
     * @var Route[]
     */
    protected array $routes = [];

    /**
     * @var  array<int, callable>
     */
    protected array $errorHandlers = [];
    protected Route $current;

    public function add(string $method, string $path, callable $handler): Route
    {
        return $this->routes[] = new Route($method, $path, $handler);
    }

    public function errorHandler(int $code, callable $handler): void
    {
        $this->errorHandlers[$code] = $handler;
    }

    public function dispatch()
    {
        $paths = $this->paths();

        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestPath = $_SERVER['REQUEST_URI'] ?? '/';

        $matching = $this->match($requestMethod, $requestPath);
        if ($matching) {
            $this->current = $matching;

            return $matching->dispatch();
        }

        if (in_array($requestPath, $paths)) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    private function paths(): array
    {
        $paths = [];

        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }

        return $paths;
    }

    public function current(): ?Route
    {
        return $this->current;
    }

    private function match(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }

        return null;
    }

    public function dispatchNotAllowed()
    {
        $this->errorHandlers[400] ??= fn() => 'not allowed';
        return $this->errorHandlers[400]();
    }

    public function dispatchNotFound()
    {
        $this->errorHandlers[404] ??= fn() => 'not found';
        return $this->errorHandlers[404]();
    }

    public function dispatchError()
    {
        $this->errorHandlers[500] ??= fn() => 'server error';
        return $this->errorHandlers[500]();
    }

    public function redirect($path) : void
    {
        header("Location: {$path}", $replace = true, $code = 301);
    }

    /**
     * @throws Exception
     */
    public function route(string $name, array $parameters = []): string
    {
        foreach ($this->routes as $route) {
            if ($route->name() === $name) {
                $finds = [];
                $replaces = [];

                foreach ($parameters as $key => $value) {
                    $finds[] = "{{$key}}";
                    $replaces[] = $value;
                    $finds[] = "{{$key}?}";
                    $replaces[] = $value;
                }

                $path = $route->path();
                $path = str_replace($finds, $replaces, $path);
                return preg_replace('#{[^}]+}#', '', $path);
            }
        }

        throw new Exception('no route with that name');
    }
}
