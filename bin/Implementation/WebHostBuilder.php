<?php

namespace bin\Implementation;

use bin\Abstraction\Classes\BaseStartup;
use bin\Abstraction\Interfaces\IHostBuilder;
use bin\Abstraction\Interfaces\IRequestsListenerCore;
use bin\Services\DependencyInjectionService\Implementation\Ninject;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;

class WebHostBuilder implements IHostBuilder
{
    use SystemReflection;
    protected static ?self $webHostInstance = null;
    private array $args;
    private ?BaseStartup $startupInstance = null;
    public function __construct(array $args)
    {
        $this->args = $args;
        $this->LoadBaseDependencies();
    }
    static function BuildHost(array $args) : IHostBuilder
    {
        if (!self::$webHostInstance) {
            self::$webHostInstance = new self($args);
        }
        return self::$webHostInstance;
    }

    public function UseStartup(string $startup): IHostBuilder
    {
        $startupWithNamespace = $this->ApplyNamespace($startup);
        $this->startupInstance = new $startupWithNamespace();
        return $this;
    }

    public function Start(): void
    {
        $listenerInstance = NinjectExecutor::GetInjectExecutor()->Inject($this->ApplyNamespace("RequestsListenerCore"));
        if ($listenerInstance instanceof IRequestsListenerCore) {
            $listenerInstance->ApplyStartup($this->startupInstance);
            $listenerInstance->HandleIncomeRequest();
        }
    }

    private function LoadBaseDependencies() : void {
        Ninject::Bind("IRoutingConfiguration")->To("RoutingConfiguration")->InRequestScope();
        Ninject::Bind("IRouting")->To("Routing")->InRequestScope();
        Ninject::Bind("IServicesConfigurator")->To("ServicesConfigurator")->InRequestScope();
        Ninject::Bind("IRoutingStateManager")->To("RoutingStateManager")->InRequestScope();
        Ninject::Bind("IRequestBody")->To("RequestBody")->InRequestScope();
        Ninject::Bind("IRequestHeader")->To("RequestHeaders")->InRequestScope();
        Ninject::Bind("IControllers")->To("ControllersManger")->InRequestScope();
        Ninject::Bind("IAttributeParser")->To("AttributeParser")->InRequestScope();
        Ninject::Bind("IStorage")->To("AppStorage")->InRequestScope();
        Ninject::Bind("IPatternRouterHelper")->To("PatternRouterHelper")->InRequestScope();

        Ninject::Bind("IAttributesParser")->To("AttributesParser")->InRequestScope();
        Ninject::Bind("IAttributesStorage")->To("AttributesStorage")->InRequestScope();


    }
}