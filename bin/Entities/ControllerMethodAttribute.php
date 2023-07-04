<?php

namespace bin\Entities;

class ControllerMethodAttribute
{
    public string $attributeName;
    public array $attributeParams;

    public function __construct(
        string $attributeName,
        array $attributeParams
    ) {
        $this->attributeName = $attributeName;
        $this->attributeParams = $attributeParams;
    }
}