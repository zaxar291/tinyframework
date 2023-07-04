<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\ControllerItem;
use bin\Entities\ControllerMethodAttribute;

interface IControllers
{
    /**
     * @return ControllerItem[]
     * @description Return All controllers, exists in the current scope
     */
    public function GetAllControllers() : array;

    public function GetController(string $partName) : ?ControllerItem;
    public function GetControllerMethod(ControllerItem $controllerItem, string $possibleMethod) : string;

    /**
     * @param string $class
     * @param string $method
     * @return ControllerMethodAttribute[]
     * @description  Get attributes for method in the class
     */
    public function GetAttributes(string $class, string $method) : array;
}