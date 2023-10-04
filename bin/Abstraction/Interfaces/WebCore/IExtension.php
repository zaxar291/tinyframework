<?php

namespace bin\Abstraction\Interfaces\WebCore;

use bin\Implementation\Contexts\HttpContext;

interface IExtension
{
    /**
     * @description Base invocation method for app extensions
     * @param HttpContext $context
     * @return HttpContext
     */
    public function Invoke(HttpContext $context) : HttpContext;
}