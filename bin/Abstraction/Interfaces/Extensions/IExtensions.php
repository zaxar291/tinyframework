<?php

namespace Start\bin\Abstraction\Interfaces\Extensions;

interface IExtensions
{
    /**
     * @description Adding new extension into the request processing
     * @param string $extension
     * @param int $prior
     */
    public function AddExtension(string $extension, int $prior) : void;
    /**
     * @description Return list of the extensions
     * @return array
     */
    public function GetExtensions() : array;
}