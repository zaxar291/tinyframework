<?php

namespace bin\Abstraction\Interfaces\WebCore;

use bin\Entities\Route;

interface IApplication
{
    /**
     * @description Enable app base routing
     */
    public function UseRouting() : void;
    /**
     * @description Map application endpoints
     * @param callable $mapper
     */
    public function MapEndpoints(callable $mapper) : void;
    /**
     * @description Map endpoint into application
     */
    public function MapEndpoint(Route $route) : void;
    /**
     * @description Enable smarty template engine
     * @param string $viewsPath
     */
    public function UseSmarty(string $viewsPath) : void;
    /**
     * @description Apply custom middleware
     * @param string $middleware
     */
    public function Use(string $middleware) : void;
    /**
     * @description Adding custom attributes, which are uses like a comments for classes, methods of controllers, uses for request's filtering
     * @param string $attribute
     */
    public function UseAttribute(string $attribute) : void;
    /**
     * @description Using pretty-formatted error pages, built-in core
     */
    public function UseDevelopmentErrorPages() : void;
    /**
     * @description Using custom controller, which will format application error's/exception's, other codes, which you can invoke by middlewares, attributes
     * @param string $handler
     */
    public function UseCustomErrorHandler(string $handler) : void;
    /**
     * @description Use this method if you want to enable parsing of the JSON request stream into the custom classes, which one you can use in the controllers
     * @param bool $allowNull - set this value to false if you want to null the whole model in case if any values from request will be null
     */
    public function UseRequestBodyParsing(bool $allowNull = true) : void;
    /**
     * @description Add custom extension to the request processing
     * @param string $extension
     * @param int $prior
     */
    public function Add(string $extension, int $prior = 1) : void;
    /**
     * @description Call this one method to enable MvcTemplate engine
     */
    public function AddMvc() : void;
}