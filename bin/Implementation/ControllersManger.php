<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IAttributeParser;
use bin\Abstraction\Interfaces\IControllers;
use bin\Entities\ControllerItem;
use bin\Entities\ControllerMethod;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;

class ControllersManger implements IControllers
{
    use SystemReflection;
    private array $controllers;
    private string $controllerIndication;
    private string $baseControllerItem;
    private IAttributeParser $attributeParser;

    public function __construct(
        IAttributeParser $attributeParser
    ) {
        $this->controllers = [];
        $this->controllerIndication = "Controller";
        $this->baseControllerItem = $this->ApplyNamespace($this->controllerIndication);
        $this->attributeParser = $attributeParser;
        $this->ParseControllers();
    }

    public function GetAllControllers() : array
    {
        return $this->controllers;
    }

    public function GetController(string $partName) : ?ControllerItem {
        foreach ($this->controllers as $controller) {
            if ($controller->controllerName == ucfirst($partName) || $controller->controllerFullName == $partName) {
                return $controller;
            }
        }
        return null;
    }

    public function GetControllerMethod(ControllerItem $controllerItem, string $possibleMethod): string
    {
        if ( count( $controllerItem->methods ) > 0 ) {
            foreach ($controllerItem->methods as $method) {
                if ( strtolower( $method->methodName ) == strtolower( $possibleMethod ) ) {
                    return $method->methodName;
                }
            }
        }
        return "";
    }

    public function GetAttributes(string $class, string $method) : array {
        $controller = $this->GetController($class);
        if ( !is_null( $controller ) && count( $controller->methods ) > 0 ) {
            foreach ( $controller->methods as $methodName ) {
                if ( $methodName->methodName == $method ) {
                    return $methodName->comments;
                }
            }
        }
        return [];
    }

    private function ParseControllers() {
        $allClasses = get_declared_classes();
        $controllersList = [];
        foreach ($allClasses as $class) {
            if ( stripos( $class, $this->controllerIndication ) !== false ) {
                $controllersList[] = $this->ApplyNamespace($class);
            }
        }
        if ( count( $controllersList ) > 0 ) {
            foreach ($controllersList as $controller) {
                if ( is_subclass_of( $controller, $this->baseControllerItem ) ) {
                    $controllerClass = $this->GetClass($controller);
                    $this->controllers[] = new ControllerItem(
                        $controllerClass->getShortName(),
                        $this->GetControllerName( $controller ),
                        $controllerClass->getNamespaceName(),
                        $this->GetControllerMethods( $controllerClass, $controller )
                    );

                }
            }
        }
    }

    private function GetControllerName($controller) : string {
        $controllerParts = explode("\\", $controller);
        return str_ireplace( $this->controllerIndication, "", $controllerParts[array_key_last( $controllerParts )] );
    }

    private function GetControllerMethods(\ReflectionClass $controllerObject, $controllerName) : array {
        $methods = [];
        $methodsList = $controllerObject->getMethods();
        if ( count( $methodsList ) > 0 ) {
            foreach ($methodsList as $method) {
                if ( $method->class === $controllerName ) {
                    $methods[] = new ControllerMethod(
                        $method->name,
                        $this->attributeParser->ParseAttributes($controllerName, $method->name)
                    );
                }
            }
        }
        return $methods;
    }
}