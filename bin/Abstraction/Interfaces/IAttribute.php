<?php

namespace bin\Abstraction\Interfaces;

interface IAttribute
{
    /**
     * @description Attribute executor base method
     */
    public function Execute(IAttributeContext $context) : IAttributeContext;
}