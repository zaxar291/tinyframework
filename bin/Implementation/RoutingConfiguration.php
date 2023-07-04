<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Entities\Route;

class RoutingConfiguration implements IRoutingConfiguration
{
    private array $routes;
    private array $routers;
    public function __construct(
    ) {
        $this->routers = [];
        $this->routes = [];
    }
    public function MapRoute(Route $route) : void
    {
        $this->routes[] = $route;
    }

    public function GetRoutes() : array
    {
        return $this->routes;
    }

    public function ApplyRouter(string $router)
    {
        $this->routers[] = $router;
    }

    public function GetRoutingHandlers() : array
    {
        return $this->routers;
    }
}