<?php

namespace bin\Implementation\WebMiddleware;

use bin\Abstraction\Interfaces\Middlewares\IMiddlewareStorage;

class MiddlewareStorage implements IMiddlewareStorage
{

    private array $middlewares;

    public function __construct() {
        $this->middlewares = [];
    }

    public function ApplyMiddleware(string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function GetAll(): array
    {
        return $this->middlewares;
    }
}