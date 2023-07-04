<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\Route;

interface IRoutingConfiguration
{
    /**
     * @description Adding new route to the collection, which will be handled next in PatternRouter class
     */
    public function MapRoute(Route $route);
    /**
     * @description Getting the list of the routes, registered in the application
     */
    public function GetRoutes() : array;
    /**
     * @description Adding custom router to the context, routing class should implement IRouter interface
     */
    public function ApplyRouter(string $router);
    /**
     * @description Getting the list of the routers registered in application
     */
    public function GetRoutingHandlers() : array;

}