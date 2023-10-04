<?php
namespace bin\Abstraction\Interfaces\Middlewares;

interface   IMiddlewareStorage
{
    /**
     * @description Adding a new middleware to the collection
     * @param string $middleware
     */
    public function ApplyMiddleware(string $middleware) : void;

    /**
     * @description Return's back all registered middlewares
     * @return array
     */
    public function GetAll() : array;

}