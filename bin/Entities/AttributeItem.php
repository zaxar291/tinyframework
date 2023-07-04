<?php

namespace bin\Entities;

class AttributeItem
{
    public string $selector;
    public string $name;

    public function __construct(
        string $selector,
        string $name
    ) {
        $this->name = $name;
        $this->selector = $selector;
    }
}