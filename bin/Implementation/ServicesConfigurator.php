<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IServicesConfigurator;
use bin\Services\DependencyInjectionService\Implementation\Ninject;

class ServicesConfigurator implements IServicesConfigurator
{
    public function AddTransient(string $abstraction, string $implementation): void
    {
        try {
            Ninject::Bind($abstraction)->To($implementation)->InTransientScope();
        } catch (\Exception $e) {

        }
    }

    public function AddRequested(string $abstraction, string $implementation): void
    {
        try {
            Ninject::Bind($abstraction)->To($implementation)->InRequestScope();
        } catch (\Exception $e) {

        }
    }

    public function AddAttribute(string $attributeClass) : void {

    }
}