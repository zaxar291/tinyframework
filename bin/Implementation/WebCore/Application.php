<?php

namespace bin\Implementation\WebCore;

use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IStorage;
use bin\Abstraction\Interfaces\Middlewares\IMiddlewareStorage;
use bin\Abstraction\Interfaces\WebCore\IApplication;
use bin\Abstraction\Interfaces\WebCore\IExtension;
use bin\Entities\Consts;
use bin\Entities\Route;
use bin\Services\DependencyInjectionService\Implementation\Ninject;
use Start\bin\Abstraction\Interfaces\Extensions\IExtensions;

class Application implements IApplication
{
    private IRoutingConfiguration $routingConfiguration;
    private IStorage $storage;
    private IMiddlewareStorage $middlewareStorage;
    private IAttributesStorage $attributesStorage;
    private IExtensions $extensions;
    public function __construct(
        IRoutingConfiguration $routingConfiguration,
        IStorage $storage,
        IMiddlewareStorage $middlewareStorage,
        IAttributesStorage $attributesStorage,
        IExtensions $extensions
    ) {
        $this->routingConfiguration = $routingConfiguration;
        $this->storage = $storage;
        $this->middlewareStorage = $middlewareStorage;
        $this->attributesStorage = $attributesStorage;
        $this->extensions = $extensions;
    }

    public function UseRouting(): void
    {
        Ninject::Bind("IRoutingExtension")->To("RoutingExtension")->InRequestScope();
        Ninject::Bind("IRoutingStateManager")->To("RoutingStateManager")->InRequestScope();
        $this->routingConfiguration->ApplyRouter("PatternRouter");
        Ninject::Bind("IPatternRouterHelper")->To("PatternRouterHelper")->InRequestScope();
    }

    public function MapEndpoints(callable $mapper): void
    {
        $mapper($this->routingConfiguration);
    }

    public function MapEndpoint(Route $route): void
    {
        $this->routingConfiguration->MapRoute($route);
    }

    public function UseSmarty(string $viewsPath): void
    {
        $this->storage->Set(Consts::$SmartyViewsDirKey, $viewsPath);
    }

    public function Use(string $middleware): void
    {
        $this->middlewareStorage->ApplyMiddleware($middleware);
    }
    public function UseAttribute(string $attribute) : void {
        $this->attributesStorage->AddAttribute($attribute);
    }
    public function UseDevelopmentErrorPages() : void {
        Ninject::Bind("IErrorPageController")->To("ErrorPageController")->InRequestScope();
    }
    public function UseCustomErrorHandler(string $handler): void
    {
        Ninject::Bind("IErrorPageController")->To($handler)->InRequestScope();
    }
    public function UseRequestBodyParsing(bool $allowNull = true) : void {
        $this->Use("JsonStreamReflectParserMiddleware");
        $this->storage->Set(Consts::$JsonStreamReflectParserMiddlewareAllowNull, $allowNull);
    }
    public function AddMvc(): void
    {
        $this->Add("MvcTemplateExtension", 3);
    }

    public function Add(string $extension, int $prior = 1): void
    {
        $this->extensions->AddExtension($extension, $prior);
    }
}