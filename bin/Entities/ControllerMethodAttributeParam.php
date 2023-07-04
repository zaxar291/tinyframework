<?php

namespace bin\Entities;

class ControllerMethodAttributeParam
{
    public string $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }
}