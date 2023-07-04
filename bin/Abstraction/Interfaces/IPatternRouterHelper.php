<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\ControllerItem;
use bin\Entities\RouteMinMaxLength;

interface IPatternRouterHelper
{
    public function TryFindMethodByRouteAttributes() : ?string;
    public function GetRouteSegments(string $routeTemplate) : array;
    public function GetSegmentsPossibleLength(array $segments) : RouteMinMaxLength;
    public function TryGetControllerMethodByDefaultMapping(ControllerItem $controllerItem) : string;
    public function GetRequestUrl() : string;
    public function GetUrlSegments() : array;
    public function SetItemToStorage(string $key, string $value) : void;
}