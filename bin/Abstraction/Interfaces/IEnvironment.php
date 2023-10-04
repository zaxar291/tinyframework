<?php

namespace bin\Abstraction\Interfaces;

interface IEnvironment extends IReflectSection
{
    /**
     * @description Returns env item by {$key} value
     * @param string $key
     * @return mixed
     */
    public function Get(string $key);

    /**
     * @description Return's true if [env] param in the appsettings.json set to the "dev" | development
     * @return bool
     */
    public function IsDevelopment() : bool;
    /**
     * @description Returns all environment items as array
     * @return array
     */
    public function ToArray() : array;

    /**
     * @description Returns all environment items as object
     * @return object
     */
    public function ToObject() : object;
}