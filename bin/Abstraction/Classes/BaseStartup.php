<?php

namespace bin\Abstraction\Classes;

use bin\Abstraction\Interfaces\IEnvironment;
use bin\Abstraction\Interfaces\Services\IServicesCollection;
use bin\Abstraction\Interfaces\WebCore\IApplication;

abstract class BaseStartup
{
    /**
     * @description Implement this function on your own to configure your application
     * @param IApplication $application
     * @param IEnvironment $environment
     */
    public function ConfigureApplication(IApplication $application, IEnvironment $environment) : void {}
    /**
     * @description You can implement this method to use dependency injection tools for your services, attributes e.t.c
     * @param IServicesCollection $servicesCollection
     */
    public function ConfigureServices(IServicesCollection $servicesCollection) : void {}
}