<?php

namespace bin\Implementation;

use bin\Abstraction\Classes\BaseStartup;
use bin\Abstraction\Interfaces\IAttributesParser;
use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Abstraction\Interfaces\IRequestsListenerCore;
use bin\Abstraction\Interfaces\IRouting;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IServicesConfigurator;
use bin\Abstraction\Interfaces\IStorage;
use bin\Services\DependencyInjectionService\Entities\InjectExtraParam;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class RequestsListenerCore implements IRequestsListenerCore
{
    private IRoutingConfiguration $routingConfiguration;
    private IServicesConfigurator $servicesConfigurator;
    private IRouting $routing;
    private IStorage $storage;
    private IAttributesParser $attributesParser;
    private IAttributesStorage $attributesStorage;
    private ?BaseStartup $startup = null;
    public function __construct(
        IRoutingConfiguration $routingConfiguration,
        IServicesConfigurator $servicesConfigurator,
        IRouting $routing,
        IStorage $storage,
        IAttributesParser $attributesParser,
        IAttributesStorage $attributesStorage
    ) {
        $this->routingConfiguration = $routingConfiguration;
        $this->servicesConfigurator = $servicesConfigurator;
        $this->routing = $routing;
        $this->storage = $storage;
        $this->attributesParser = $attributesParser;
        $this->attributesStorage = $attributesStorage;

        $this->LoadDefaults();
    }

    public function ApplyStartup(BaseStartup $startup) : self
    {
        $this->startup = $startup;
        return $this;
    }

    public function HandleIncomeRequest(): void
    {
        $this->startup->ConfigureServices($this->servicesConfigurator, $this->attributesStorage);
        $this->startup->ConfigureRoutes($this->routingConfiguration);
        $route = $this->routing->TryFindHandler();
        if ( !is_null( $route ) ) {
            $this->attributesParser->ExecuteParsing($route->controllerName, $route->methodName);
            $actionResult = NinjectExecutor::GetInjectExecutor()->Inject($route->controllerName, $route->methodName, $this->MapStorageParams());
            echo $actionResult;
        }
    }

    private function MapStorageParams() : array {
        $params = [];
        foreach ( $this->storage->GetAll() as $storageKey => $storageValue ) {
            $params[] = new InjectExtraParam(((int)$storageValue > 0 ? "int" : gettype( $storageValue )), $storageKey, $storageValue );
        }
        return $params;
    }

    private function LoadDefaults() {
        if ( is_array( $_REQUEST ) && count( $_REQUEST ) > 0 ) {
            foreach ($_REQUEST as $key => $value) {
                $this->storage->Set($key, $value);
            }
        }
        $this->routingConfiguration->ApplyRouter("BaseRouter");
        $this->storage->Set("app.storage.mappings", json_encode([["GET", "Get"], ["GET", "Index"], ["POST", "Post"]]));
    }
}