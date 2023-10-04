<?php

namespace bin\Abstraction\Interfaces;

interface IStorage
{
    /**
     * @description Getting specified value by it key
     * @param string $key
     * @return mixed
     */
    public function Get(string $key);
    /**
     * @description Pushing new value into the storage, storage can have different value types
     * @param string $key
     * @param mixed $value
     */
    public function Set(string $key, $value) : void;
    /**
     * @description Return all stored values as array
     * @return array
     */
    public function GetAll() : array;

}