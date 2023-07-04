<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IControllers;
use bin\Abstraction\Interfaces\IPatternRouterHelper;
use bin\Abstraction\Interfaces\IRouter;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Entities\CurrentRouteState;

class PatternRouter implements IRouter
{
    private IControllers $controllers;
    private IRoutingConfiguration $routingConfiguration;
    private IRoutingStateManager $routingState;
    private IPatternRouterHelper $patternRouterHelper;

    public function __construct(
        IRoutingStateManager $routingState,
        IRoutingConfiguration $routingConfiguration,
        IControllers $controllers,
        IPatternRouterHelper $patternRouterHelper
    ) {
        $this->routingState = $routingState;
        $this->controllers = $controllers;
        $this->routingConfiguration = $routingConfiguration;
        $this->patternRouterHelper = $patternRouterHelper;
    }

    public function Route(): void
    {
        $urlSegments = $this->patternRouterHelper->GetUrlSegments();
        $segmentsCount = count($urlSegments);
        $routes = $this->routingConfiguration->GetRoutes();

        if ( count( $routes ) > 0 ) {
            foreach ($routes as $route) {
                $this->routingState->UpdateState(new CurrentRouteState("", ""));
                $routeSegments = $this->patternRouterHelper->GetRouteSegments($route->pattern);
                $routeSegmentsCount = $this->patternRouterHelper->GetSegmentsPossibleLength($routeSegments);
                if ( ( $routeSegmentsCount->minLength <= $segmentsCount || $routeSegmentsCount->maxLength >= $segmentsCount ) && count( $routeSegments ) > 0 ) {
                    foreach ($routeSegments as $routeSegmentIndex => $routeSegment) {
                        if (isset($urlSegments[$routeSegmentIndex])) {
                            $currentState = $this->routingState->GetCurrentState();
                            $matchedPartFromUrl = $urlSegments[$routeSegmentIndex];
                            $controllerItem = $this->controllers->GetController($matchedPartFromUrl);
                            if ( !is_null( $controllerItem ) && trim( $currentState->controllerName ) == "" ) {
                                $this->routingState->UpdateState(new CurrentRouteState(
                                    $route->pattern,
                                    get_class( $this ),
                                    true,
                                    $controllerItem->controllerFullName,
                                    (!isset( $urlSegments[$routeSegmentIndex + 1] )) ? $this->patternRouterHelper->TryGetControllerMethodByDefaultMapping($controllerItem) : ""
                                ));
                            } else {
                                $currentState = $this->routingState->GetCurrentState();
                                if ( trim ( $currentState->controllerName ) !== "" ) {
                                    $controllerItem = $this->controllers->GetController($currentState->controllerName);
                                    $controllerMethod = $this->controllers->GetControllerMethod($controllerItem, $matchedPartFromUrl);
                                    if ( trim( $controllerMethod ) !== "" ) {
                                        $this->routingState->UpdateState(new CurrentRouteState(
                                            $route->pattern,
                                            get_class( $this ),
                                            true,
                                            $controllerItem->controllerFullName,
                                            $controllerMethod
                                        ));
                                    } else {
                                        $this->patternRouterHelper->SetItemToStorage($routeSegment->segmentTemplate, $matchedPartFromUrl);
                                    }
                                } else {
                                    $this->patternRouterHelper->SetItemToStorage($routeSegment->segmentTemplate, $matchedPartFromUrl);
                                }
                            }
                        }
                    }
                }
                $currentState = $this->routingState->GetCurrentState();
                if ( $currentState->controllerName !== "" && trim ( $currentState->methodName ) == "" ) {
                    $controllerMethod = $this->patternRouterHelper->TryFindMethodByRouteAttributes();
                    if ( !is_null( $controllerMethod ) ) {
                        $this->routingState->UpdateState(new CurrentRouteState(
                            $currentState->url,
                            get_class( $this ),
                            true,
                            $currentState->controllerName,
                            $controllerMethod
                        ));
                        break;
                    }
                }
                if ( trim( $currentState->controllerName ) == "" && trim( $currentState->methodName ) == "" ) {
                    if ( ( $routeSegmentsCount->minLength <= $segmentsCount || $routeSegmentsCount->maxLength >= $segmentsCount ) && count( $routeSegments ) > 0 ) {
                        foreach ($routeSegments as $routeSegmentIndex => $routeSegment) {
                            if (!isset($urlSegments[$routeSegmentIndex])) {
                                if ( trim( $routeSegment->segmentDefaultValue ) !== "" ) {
                                    $controllerItem = $this->controllers->GetController($routeSegment->segmentDefaultValue);
                                    if ( !is_null( $controllerItem ) ) {
                                        $this->routingState->UpdateState(new CurrentRouteState(
                                            $route->pattern,
                                            get_class( $this ),
                                            true,
                                            $controllerItem->controllerFullName,
                                            $this->patternRouterHelper->TryGetControllerMethodByDefaultMapping($controllerItem)
                                        ));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}