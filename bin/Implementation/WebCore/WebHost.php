<?php

namespace bin\Implementation\WebCore;

use bin\Abstraction\Interfaces\WebCore\IHost;
use bin\Abstraction\Interfaces\WebCore\IHostBuilder;
use bin\Services\DependencyInjectionService\Implementation\Ninject;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class WebHost implements IHost
{
    public static ?IHostBuilder $host = null;

    public static function CreateBuilder(array $args = []): IHostBuilder
    {
        if ( !self::$host ) {
            self::LoadCoreModules();
            $instance = NinjectExecutor::GetInjectExecutor()->Inject("WebHostBuilder");
            if ( $instance instanceof IHostBuilder) {
                self::$host = $instance;
            } else {
                throw new \Exception("Failed to create builder, request processing aborted");
            }
        }
        return self::$host;
    }

    protected static function LoadCoreModules() : void {
        Ninject::Bind("IRoutingConfiguration")->To("RoutingConfiguration")->InRequestScope();


        Ninject::Bind("IAttributesParser")->To("AttributesExtension")->InRequestScope();
        Ninject::Bind("IAttributeParser")->To("AttributeParser")->InRequestScope();
        Ninject::Bind("IAttributesStorage")->To("AttributesStorage")->InRequestScope();
        Ninject::Bind("IMiddlewareStorage")->To("MiddlewareStorage")->InRequestScope();
        Ninject::Bind("IMiddlewareExtension")->To("MiddlewareExtension")->InRequestScope();

        Ninject::Bind("IExtensions")->To("Extensions")->InRequestScope();
        Ninject::Bind("IApplication")->To("Application")->InRequestScope();
        Ninject::Bind("IEnvironment")->To("Environment")->InRequestScope();
        Ninject::Bind("IStorage")->To("AppStorage")->InRequestScope();
        Ninject::Bind("IRequestBody")->To("RequestBody")->InRequestScope();
        Ninject::Bind("IRequestHeader")->To("RequestHeaders")->InRequestScope();
        Ninject::Bind("IControllers")->To("ControllersManger")->InRequestScope();
        Ninject::Bind("IServicesCollection")->To("ServicesCollection")->InRequestScope();
    }
}