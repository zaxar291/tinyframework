<?php

namespace bin\Abstraction\Interfaces;

interface IServicesConfigurator
{
    /**
     * @param string $abstraction
     * @param string  $implementation
     * @description adding of transient-scoped service, which will be re-created in each call
     */
    public function AddTransient(string $abstraction, string $implementation) : void;

    /**
     * @param string $abstraction
     * @param string  $implementation
     * @description adding of request-scoped service, which will be created one-time per request
     */
    public function AddRequested(string $abstraction, string $implementation) : void;

    /**
     * @param string $attributeClass
     * @description adding a new attribute to the app context, be sure your attribute implement's IAttribute interface
     */
    public function AddAttribute(string $attributeClass) : void;
}