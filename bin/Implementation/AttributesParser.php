<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IAttribute;
use bin\Abstraction\Interfaces\IAttributeContext;
use bin\Abstraction\Interfaces\IAttributesParser;
use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Abstraction\Interfaces\IControllers;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class AttributesParser implements IAttributesParser
{

    private IAttributesStorage $storage;
    private IControllers $controllers;

    public function __construct(
        IAttributesStorage $storage,
        IControllers $controllers
    ) {
        $this->storage = $storage;
        $this->controllers = $controllers;
    }

    public function ExecuteParsing(string $class, string $method) : IAttributeContext
    {
        $context = new AttributeContext($class, $method);
        $attributes = $this->storage->GetAttributes(
            $this->controllers->GetAttributes($class, $method)
        );
        if ( count( $attributes ) > 0 ) {
            foreach ( $attributes as $attribute ) {
                $attributeInstance = NinjectExecutor::GetInjectExecutor()->Inject($attribute->name);
                if ( $attributeInstance instanceof IAttribute ) {
                    $context = $attributeInstance->Execute($context);
                    if ( $context->Rejected() ) {
                        echo "Busted!";
                        die;
                    }
                }
            }
        }

        return $context;
    }
}