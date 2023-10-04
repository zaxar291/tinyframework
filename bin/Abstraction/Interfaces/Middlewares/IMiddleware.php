<?php

namespace bin\Abstraction\Interfaces\Middlewares;

use bin\Implementation\Contexts\HttpContext;

interface IMiddleware
{
    /**
     * @description All of middlewares have to implement this interface for correct request processing, middlewares can also reject request processing
     */
    public function Invoke(HttpContext $context) : HttpContext;
}