<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\ControllerMethodAttribute;

interface IAttributeParser {
    /**
     * @description Return all the attributes for the processing to the class method
     * @param string $className
     * @param string $methodName
     * @return ControllerMethodAttribute[]
     */
    public function ParseAttributes(string $className, string $methodName) : array;
}