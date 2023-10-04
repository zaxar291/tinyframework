<?php
namespace bin\Services;
use bin\Abstraction\Interfaces\Services\IServicesCollection;
use bin\Services\DependencyInjectionService\Implementation\Ninject;

class ServicesCollection implements IServicesCollection
{

    public function AddScoped(string $abstract, string $implementation): void
    {
        Ninject::Bind($abstract)->To($implementation)->InRequestScope();
    }

    public function AddTransient(string $abstract, string $implementation): void
    {
        Ninject::Bind($abstract)->To($implementation)->InTransientScope();
    }
}