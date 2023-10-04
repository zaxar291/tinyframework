<?php
namespace Start;
use bin\Abstraction\Classes\BaseStartup;
use bin\Abstraction\Interfaces\IEnvironment;
use bin\Abstraction\Interfaces\IRoutingConfiguration;
use bin\Abstraction\Interfaces\Services\IServicesCollection;
use bin\Abstraction\Interfaces\WebCore\IApplication;
use bin\Entities\Route;

class Startup extends BaseStartup
{

    public function ConfigureApplication(IApplication $application, IEnvironment $environment) : void
    {
        $application->UseDevelopmentErrorPages();
        if ( $environment->IsDevelopment() ) {
            $application->UseDevelopmentErrorPages();
        } else {
//            $application->UseCustomErrorHandler();
        }
        $application->AddMvc();
        $application->UseRouting();
        $application->MapEndpoints(function(IRoutingConfiguration $mapper) {
            $mapper->MapRoute(new Route(
                "defaultMapping",
                "{lang?}/{controller}/{action?}"
            ));
            $mapper->MapRoute(new Route(
                "testWithNewws",
                "test/{id}"
            ));
            $mapper->MapRoute(new Route(
                "testWithNewwsL",
                "{lang?}/test/{id}"
            ));
            $mapper->MapRoute(new Route(
                "homePage",
                "{lang?}/{controller=Main}"
            ));
        });
        $application->UseRequestBodyParsing();
    }

    public function ConfigureServices(IServicesCollection $servicesCollection): void
    {

    }
}
