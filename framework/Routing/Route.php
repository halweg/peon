<?php

namespace Framework\Routing;

class Route
{
    public function __construct(
        protected string   $method,
        protected string   $path,
        protected mixed    $handler,
    )
    {
    }

    public function method() :string
    {
        return $this->method;
    }

    public function path() :string
    {
        return $this->path;
    }

    public function matches(string $method, string $path) : bool
    {
        return $this->path == $path && $method == $this->method;
    }

    public function dispatch()
    {
        return call_user_func($this->handler);
    }

}