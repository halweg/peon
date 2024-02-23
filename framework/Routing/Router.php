<?php

namespace Framework\Routing;
use JetBrains\PhpStorm\NoReturn;

class Router
{
    protected array $errorHandlers = [];

    /**
     * @var Route[]
     */
    protected array $routes = [];

    public function add(
        string   $method,
        string   $path,
        callable $handler
    ): Route
    {
        return $this->routes[] = new Route(
            $method, $path, $handler
        );
    }

    public function dispatch(): mixed
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? "GET";
        $requestPath   = $_SERVER['REQUEST_URI'] ?? "/";
        $matching = $this->matches($requestMethod, $requestPath);
        if ($matching) {
            try {
                return $matching->dispatch();
            }
            catch (\Throwable $e) {
                return $this->dispatchError();
            }
        }
        if (in_array($requestPath, $this->paths())) {
            return $this->dispatchNotAllowed();
        }
        return $this->dispatchNotFound();
    }

    public function matches(string $method, string $path) : ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * @return string[]
     */
    public function paths() : array
    {
        $path = [];
        foreach ($this->routes as $route) {
            $path[] = $route->path();
        }
        return $path;
    }

    public function redirect(string $path): void
    {
        header("Location: {$path}", $replace = true, $code = 301);
    }

    public function errorHandler(int $code, callable $handler): void
    {
        $this->errorHandlers[$code] = $handler;
    }
    public function dispatchNotAllowed()
    {
        $this->errorHandlers[400] ??= fn() => "not allowed";
        return $this->errorHandlers[400]();
    }
    public function dispatchNotFound()
    {
        $this->errorHandlers[404] ??= fn() => "not found";
        return $this->errorHandlers[404]();
    }

    public function dispatchError()
    {
        $this->errorHandlers[500] ??= fn() => "server router dispatch error";
        return $this->errorHandlers[500]();
    }

}