<?php

namespace bin\Abstraction\Interfaces;

interface IReflectSection
{
    /**
     * @description Create section with some data, where section can be class name (uses directly when any instance calls), or any other name, which can be directly mapped by the [TypeMap] attribute
     * @param string $sectionName
     * @param mixed $data
     */
    public function CreateSection(string $sectionName, $data) : void;

    /**
     * @description Return section by it name, null will be returned if section won't be exists
     * @param string $sectionName
     * @return mixed|null
     */
    public function GetSection(string $sectionName);
}