<?php

namespace Start\bin\Implementation\MvcRouting;

use bin\Abstraction\Interfaces\IControllers;
use bin\Abstraction\Interfaces\IRouter;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IRoutingStateManager;

class BaseRouter implements IRouter
{

    private IRoutingStateManager $routingState;
    private IRoutingConfiguration $routingConfiguration;
    private IControllers $controllers;
    private array $routes;

    public function __construct(
        IRoutingStateManager $routingState,
        IRoutingConfiguration $routingConfiguration,
        IControllers $controllers
    )
    {
        $this->routingState = $routingState;
        $this->routingConfiguration = $routingConfiguration;
        $this->controllers = $controllers;
        $this->routes = $this->routingConfiguration->GetRoutes();
    }

    public function Route(): void
    {
        $currentState = $this->routingState->GetCurrentState();
        if ( count( $this->routes ) > 0 ) {
            foreach ($this->routes as $route) {
                if ( $currentState->url == $route->pattern ) {
                    echo "That is out router";
                }
            }
        }
    }

    public function HavePatterns(string $route) : bool {
        return stripos( "{", $route ) && stripos( "}", $route );
    }
}