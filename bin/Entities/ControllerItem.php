<?php

namespace bin\Entities;

class ControllerItem
{
    public string $controllerFullName;
    public string $controllerName;
    public string $nameSpace;
    public array $methods;

    public function __construct(
        string $controllerFullName,
        string $controllerName,
        string $nameSpace,
        array $methods = []
    ) {
        $this->controllerFullName = $controllerFullName;
        $this->controllerName = $controllerName;
        $this->nameSpace = $nameSpace;
        $this->methods = $methods;
    }
}