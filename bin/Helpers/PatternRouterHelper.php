<?php

namespace bin\Helpers;
use bin\Abstraction\Interfaces\IControllers;
use bin\Abstraction\Interfaces\IPatternRouterHelper;
use bin\Abstraction\Interfaces\IRequestBody;
use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Abstraction\Interfaces\IStorage;
use bin\Entities\ControllerItem;
use bin\Entities\RouteMinMaxLength;
use bin\Entities\RouteSegment;

class PatternRouterHelper implements IPatternRouterHelper
{

    private IStorage $storage;
    private IRoutingStateManager $stateManager;
    private IControllers $controllers;
    private IRequestBody $requestBody;
    private array $defaultMapping;
    private string $storageMappingKey;

    public function __construct(
        IStorage $storage,
        IRoutingStateManager $stateManager,
        IControllers $controllers,
        IRequestBody $requestBody
    )
    {
        $this->storage = $storage;
        $this->stateManager = $stateManager;
        $this->controllers = $controllers;
        $this->requestBody = $requestBody;

        $this->storageMappingKey = "app.storage.mappings";

        $this->ApplyDefaultMapping();
    }

    public function TryFindMethodByRouteAttributes(): ?string
    {
        $currentState = $this->stateManager->GetCurrentState();
        $controllerDescriptor = $this->controllers->GetController( $currentState->controllerName );
        if ( count( $controllerDescriptor->methods ) > 0 ) {
            foreach ( $controllerDescriptor->methods as $method ) {
                if ( count( $method->comments ) > 0 ) {
                    foreach ($method->comments as $comment) {
                        if ( $comment->attributeName == "Route" ) {
                            if ( count( $comment->attributeParams ) > 0 ) {
                                foreach ($comment->attributeParams as $attributeParam) {
                                    if ( $currentState->url == $attributeParam->name ) {
                                        return $method->methodName;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    public function GetRouteSegments(string $routeTemplate): array
    {
        $segments = explode("/", $routeTemplate);
        $formattedRouteSegments = [];
        if ( count( $segments ) > 0 ) {
            foreach ($segments as $segment) {
                $formattedRouteSegments[] = new RouteSegment(
                    $this->SelectSegmentPattern( $segment ),
                    $this->IsSegmentRequired( $segment ),
                    $this->IsPatternSegment( $segment ),
                    $this->SelectSegmentDefaultValue( $segment )
                );
            }
        }
        return $formattedRouteSegments;
    }

    public function GetSegmentsPossibleLength(array $segments): RouteMinMaxLength
    {
        $routeMinMaxLength = new RouteMinMaxLength();
        if ( count( $segments ) > 0 ) {
            foreach ( $segments as $segment ) {
                if ( trim( $segment->segmentDefaultValue ) == "" ) {
                    $routeMinMaxLength->maxLength++;
                    if ($segment->isSegmentRequired) {
                        $routeMinMaxLength->minLength++;
                    }
                }
            }
        }
        return $routeMinMaxLength;
    }

    public function TryGetControllerMethodByDefaultMapping(ControllerItem $controllerItem): string
    {
        $t = "";
        foreach ($controllerItem->methods as $method) {
            $t = $this->GetByMethodMapping($method->methodName);
            if (trim($t) !== "") {
                return $t;
            }
        }
        return $t;
    }

    public function GetRequestUrl(): string
    {
        return strtok(str_ireplace("/tinyframework", "", $this->requestBody->GetRequestItem("REQUEST_URI")->value), "?");
    }

    public function GetUrlSegments() : array {
        return array_values(array_filter(explode("/", $this->GetRequestUrl()), function($p){
            return $p !== "";
        }));
    }

    public function SetItemToStorage(string $key, string $value) : void {
        $this->storage->Set($this->PrepareStorageItemKey($key), $value);
    }

    private function PrepareStorageItemKey(string $key) : string {
        return str_ireplace(["{", "}", "?"], "", $key);
    }

    private function GetByMethodMapping(string $method) : string {
        foreach ($this->defaultMapping as $value) {
            if ($value[1] == $method) {
                return $value[1];
            }
        }
        return "";
    }

    private function IsPatternSegment(string $segment) : bool {
        return stripos( $segment, "{" ) !== false && stripos( $segment, "}" ) !== false;
    }

    private function IsSegmentRequired(string $segment) : bool {
        if ( $this->IsPatternSegment( $segment ) && !stripos($segment, "?")) {
            return true;
        }

        return !stripos( $segment, "?" );
    }

    private function ApplyDefaultMapping() : void {
        $this->defaultMapping = [["GET", "Get"], ["GET", "Index"], ["POST", "Post"]];
    }

    private function SelectSegmentPattern(string $segment) : string {
        $segmentParts = explode("=", $segment);
        return "{" . str_ireplace(["{", "}"], "", $segmentParts[0]) . "}";
    }
    private function SelectSegmentDefaultValue(string $segment) : string {
        $segmentParts = explode("=", $segment);
        if ( isset( $segmentParts[1] ) ) {
            return str_ireplace(["{", "}"], "", $segmentParts[1]);
        }
        return "";
    }
}