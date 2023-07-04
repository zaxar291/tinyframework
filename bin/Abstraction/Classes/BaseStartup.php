<?php

namespace bin\Abstraction\Classes;

use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IServicesConfigurator;

abstract class BaseStartup
{
    public abstract function ConfigureRoutes(IRoutingConfiguration $routing) : void;
    public abstract function ConfigureServices(IServicesConfigurator $servicesConfigurator, IAttributesStorage $attributesStorage) : void;
}