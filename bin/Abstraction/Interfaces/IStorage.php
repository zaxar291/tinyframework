<?php

namespace bin\Abstraction\Interfaces;

interface IStorage
{
    public function Get(string $key) : string;
    public function Set(string $key, string $value);
    public function GetAll() : array;

}