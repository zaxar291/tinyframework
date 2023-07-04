<?php

namespace bin\Abstraction\Interfaces;

interface IAttributesParser
{
    /**
     * @param string $class
     * @param string $method
     * @return IAttributeContext
     * @description Executor for attributes parsing
     */
    public function ExecuteParsing(string $class, string $method) : IAttributeContext;
}