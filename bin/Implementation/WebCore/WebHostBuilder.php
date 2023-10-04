<?php

namespace bin\Implementation\WebCore;

use bin\Abstraction\Classes\BaseStartup;
use bin\Abstraction\Interfaces\IEnvironment;
use bin\Abstraction\Interfaces\Services\IServicesCollection;
use bin\Abstraction\Interfaces\WebCore\IApplication;
use bin\Abstraction\Interfaces\WebCore\IHostBuilder;
use bin\Abstraction\Interfaces\WebCore\IHostCore;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;
use Start\bin\Abstraction\Interfaces\Extensions\IExtensions;

class WebHostBuilder implements IHostBuilder
{
    use SystemReflection;
    private array $args;
    private IApplication $application;
    private IServicesCollection $servicesCollection;
    private IEnvironment $environment;
    private IExtensions $extensions;
    public function __construct(
        IApplication $application,
        IServicesCollection $servicesCollection,
        IEnvironment $environment,
        IExtensions $extensions
    )
    {
        $this->args = [];
        $this->application = $application;
        $this->servicesCollection = $servicesCollection;
        $this->environment = $environment;
        $this->extensions = $extensions;

        $this->LoadDefaultModules();
    }

    public function UseStartup(string $startup): IHostBuilder
    {
        $startupWithNamespace = $this->ApplyNamespace($startup);
        if ( class_exists( $startupWithNamespace ) ) {
            $instance = new $startupWithNamespace();
            if ( $instance instanceof BaseStartup ) {
                $instance->ConfigureApplication($this->application, $this->environment);
                $instance->ConfigureServices($this->servicesCollection);
            }
        }
        return $this;
    }

    public function UseRouting(): IHostBuilder
    {
        $this->application->UseRouting();
        return $this;
    }

    public function MapEndpoints(callable $mapper): IHostBuilder
    {
        $this->application->MapEndpoints($mapper);
        return $this;
    }

    public function UseSmarty(string $viewsPath): IHostBuilder
    {
        $this->application->UseSmarty($viewsPath);
        return $this;
    }

    public function UseServices(callable $servicesCollection): IHostBuilder
    {
        $servicesCollection($this->servicesCollection);
        return $this;
    }

    public function Use(string $middleware): IHostBuilder
    {
        $this->application->Use($middleware);
        return $this;
    }

    public function UseAttribute(string $attribute) : IHostBuilder {
        $this->application->UseAttribute($attribute);
        return $this;
    }

    public function UseDevelopmentErrorPages(): IHostBuilder
    {
        $this->application->UseDevelopmentErrorPages();
        return $this;
    }

    public function UseCustomErrorHandler(string $handler): IHostBuilder
    {
        $this->application->UseCustomErrorHandler($handler);
        return $this;
    }
    public function UseRequestBodyParsing(bool $allowNull = true): IHostBuilder
    {
        $this->application->UseRequestBodyParsing($allowNull);
        return $this;
    }
    public function MapEnv(callable $envMapper): IHostBuilder
    {
        return $this;
    }

    public function Add(string $extension, int $prior = 1) : IHostBuilder
    {
        $this->extensions->AddExtension($extension, $prior);
        return $this;
    }
    public function AddMvc(): IHostBuilder
    {
        $this->Add("MvcTemplateExtension", 3);
        return $this;
    }
    public function Process(): void
    {
        $listenerInstance = NinjectExecutor::GetInjectExecutor()->Inject($this->ApplyNamespace("WebHostCore"));
        if ($listenerInstance instanceof IHostCore) {
            $listenerInstance->Process();
        } else {
            throw new \Exception("Failed to create requests listener");
        }
    }

    private function LoadDefaultModules() {
        $this->Use("RequestMetadataMiddleware");
        $this->Add("RoutingExtension", 0);
        $this->Add("MiddlewareExtension");
        $this->Add("AttributesExtension", 2);

        $this->UseAttribute("HttpGetAttribute");
        $this->servicesCollection->AddScoped("IHttpResponseCodesHelper", "HttpResponseCodesHelper");
    }
}