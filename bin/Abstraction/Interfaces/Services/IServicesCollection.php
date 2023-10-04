<?php

namespace bin\Abstraction\Interfaces\Services;

interface IServicesCollection
{
    /**
     * @description Allows you to register custom service in transient scope (service will be created once)
     * @param string $abstract
     * @param string $implementation
     */
    public function AddScoped(string $abstract, string $implementation) : void;

    /**
     * @description Allows you to register custom service in transient scope (service will be created each time it called)
     * @param string $abstract
     * @param string $implementation
     */
    public function AddTransient(string $abstract, string $implementation) : void;
}