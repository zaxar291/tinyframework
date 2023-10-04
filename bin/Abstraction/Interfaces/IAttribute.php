<?php

namespace bin\Abstraction\Interfaces;

use bin\Implementation\Contexts\HttpContext;

interface IAttribute
{
    /**
     * @description Attribute executor base method
     * @param HttpContext $context
     * @return HttpContext
     */
    public function Execute(HttpContext $context) : HttpContext;
}