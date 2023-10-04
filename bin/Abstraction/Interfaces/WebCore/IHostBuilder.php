<?php

namespace bin\Abstraction\Interfaces\WebCore;

interface IHostBuilder {
    /**
     * @description This one method allows you to create custom Startup class, where you can map services, endpoints or add anything you need
     * @param string $startup
     * @return IHostBuilder
     */
    public function UseStartup(string $startup) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to enable app base routing
     * @return IHostBuilder
     */
    public function UseRouting() : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to map application endpoints
     * @param callable $mapper
     * @return IHostBuilder
     */
    public function MapEndpoints(callable $mapper) : self;
    /**
     * @description Enable smarty template engine
     * @param string $viewsPath
     * @return IHostBuilder
     */
    public function UseSmarty(string $viewsPath) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to inject your services into the application
     * @param callable $servicesCollection
     * @return IHostBuilder
     */
    public function UseServices(callable $servicesCollection) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to apply custom middleware
     * @param string $middleware
     * @return IHostBuilder
     */
    public function Use(string $middleware) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to map dev environment
     * @param callable $envMapper
     * @return IHostBuilder
     */
    public function MapEnv(callable $envMapper) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to add custom attributes, which are uses like a comments for classes, methods of controllers, uses for request's filtering
     * @param string $attribute
     * @return IHostBuilder
     */
    public function UseAttribute(string $attribute) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to using pretty-formatted error pages, built-in core
     * @return self
     */
    public function UseDevelopmentErrorPages() : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to using custom controller, which will format application error's/exception's, other codes, which you can invoke by middlewares, attributes
     * @param string $handler
     */
    public function UseCustomErrorHandler(string $handler) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to use this method if you want to enable parsing of the JSON request stream into the custom classes, which one you can use in the controllers
     * @param bool $allowNull - set this value to false if you want to null the whole model in case if any values from request will be null
     * @return self
     */
    public function UseRequestBodyParsing(bool $allowNull = true) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to add custom extension to the request processing
     * @param string $extension
     * @param int $prior
     * @return self
     */
    public function Add(string $extension, int $prior = 1) : self;
    /**
     * @description If you don't want to use custom [Startup] class you can call this one method to enable MvcTemplate engine
     * @return self
     */
    public function AddMvc() : self;
    /**
     * @description Launch request processing
     */
    public function Process() : void;
}