<?php

namespace bin\Implementation\Extensions;

use bin\Abstraction\Interfaces\IRouter;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Abstraction\Interfaces\WebCore\IExtension;
use bin\Entities\CurrentRouteState;
use bin\Implementation\Contexts\HttpContext;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class RoutingExtension implements IExtension
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
    public function Invoke(HttpContext $context): HttpContext
    {
        $allRouters = $this->routingConfiguration->GetRoutingHandlers();
        if ( count( $allRouters ) > 0 ) {
            foreach ( $allRouters as $router ) {
                $routeInstance = NinjectExecutor::GetInjectExecutor()->Inject($router);
                if ($routeInstance instanceof IRouter) {
                    $routeInstance->Route();
                }
                $state = $this->stateManager->GetCurrentState();
                if ( $this->IsHandlerFound( $state ) ) {
                    break;
                }
            }
        }
        return $context;
    }
    private function IsHandlerFound(CurrentRouteState $state) : bool {
        return trim ( $state->controllerName ) !== "" && trim ( $state->methodName ) !== "" && $state->isFound;
    }
}