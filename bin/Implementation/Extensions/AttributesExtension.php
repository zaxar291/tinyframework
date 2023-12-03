<?php

namespace bin\Implementation\Extensions;

use bin\Abstraction\Interfaces\IAttribute;
use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Abstraction\Interfaces\IControllers;
use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Abstraction\Interfaces\WebCore\IExtension;
use bin\Implementation\Contexts\HttpContext;
use bin\Services\DependencyInjectionService\Entities\InjectExtraParam;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;

class AttributesExtension implements IExtension
{
    use SystemReflection;
    private IAttributesStorage $storage;
    private IControllers $controllers;
    private IRoutingStateManager $routingStateManager;

    public function __construct(
        IAttributesStorage $storage,
        IControllers $controllers,
        IRoutingStateManager $routingStateManager
    ) {
        $this->storage = $storage;
        $this->controllers = $controllers;
        $this->routingStateManager = $routingStateManager;
    }

    public function Invoke(HttpContext $context): HttpContext
    {
        $state = $this->routingStateManager->GetCurrentState();
        if ( !$state->isFound ) {
            return $context->Reject(404);
        }
        $attributes = $this->storage->GetAttributes(
            $this->controllers->GetAttributes($state->controllerName, $state->methodName)
        );
        $args = [];
        if ( count( $attributes ) > 0 ) {
            foreach ( $attributes as $attribute ) {
                $constructParameters = $this->GetMethod($this->ApplyNamespace($attribute->name), "__construct")->getParameters();
                if ( count( $constructParameters ) > 0 ) {
                    $customParamsCounter = 0;
                    foreach ($constructParameters as $key => $parameter) {
                        if ( $this->IsParsableType( $parameter->getType() ) ) {
                            $args[] = new InjectExtraParam(
                                $parameter->getType(),
                                $parameter->getName(),
                                $this->FormatValue($parameter->getType(), ($attribute->values[$customParamsCounter]->name ?? ""))
                            );
                            $customParamsCounter++;
                        }
                    }
                }
                $attributeInstance = NinjectExecutor::GetInjectExecutor()->Inject($attribute->name, null, $args);
                if ( $attributeInstance instanceof IAttribute ) {
                    $context = $attributeInstance->Execute($context);
                    if ( $context->Rejected() ) {
                        return $context;
                    }
                }
            }
        }
        return $context;
    }

    private function IsParsableType(string $type) : bool {
        return in_array($type, ["string", "bool", "integer", "float", "int"]);
    }

    private function FormatValue(string $type, $value) {
        if ( $type == "string" ) {
            return (string)$value;
        } else if ( $type == "bool" ) {
            return $value == "true";
        } else if ( $type == "integer" ) {
            return (int)$value;
        }
        return (float)$value;
    }
}