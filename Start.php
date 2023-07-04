<?php
namespace Start;
use bin\Abstraction\Classes\BaseStartup;
use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\IServicesConfigurator;
use bin\Entities\Route;

class Startup extends BaseStartup
{
    public function ConfigureRoutes(IRoutingConfiguration $routing): void
    {
        $routing->ApplyRouter("PatternRouter");
        $routing->MapRoute(new Route(
            "defaultMapping",
            "{lang?}/{controller}/{action?}"
        ));
        $routing->MapRoute(new Route(
            "testNewsRoute",
            "{lang?}/test/{id}"
        ));
        $routing->MapRoute(new Route(
            "homePage",
            "{lang?}/{controller=Main}"
        ));
    }

    public function ConfigureServices(IServicesConfigurator $servicesConfigurator, IAttributesStorage $attributesStorage): void
    {
        $attributesStorage->AddAttribute("HttpGetAttribute");
    }
}
