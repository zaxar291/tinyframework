<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IRouter;
use bin\Abstraction\Interfaces\IRouting;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Entities\CurrentRouteState;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class Routing implements IRouting
{
    private IRoutingConfiguration $routingConfiguration;
    private IRoutingStateManager $stateManager;
    public function __construct(
        IRoutingConfiguration $configuration,
        IRoutingStateManager $stateManager
    ) {
        $this->routingConfiguration = $configuration;
        $this->stateManager = $stateManager;
    }

    public function TryFindHandler() : ?CurrentRouteState {
        $allRouters = $this->routingConfiguration->GetRoutingHandlers();
        if ( count( $allRouters ) > 0 ) {
            foreach ( $allRouters as $router ) {
                $routeInstance = NinjectExecutor::GetInjectExecutor()->Inject($router);
                if ($routeInstance instanceof IRouter) {
                    $routeInstance->Route();
                }
                $state = $this->stateManager->GetCurrentState();
                if ( $this->IsHandlerFound( $state ) ) {
                    return $state;
                }
            }
        }

        return null;
    }

    private function IsHandlerFound(CurrentRouteState $state) : bool {
        return trim ( $state->controllerName ) !== "" && trim ( $state->methodName ) !== "" && $state->isFound;
    }
}