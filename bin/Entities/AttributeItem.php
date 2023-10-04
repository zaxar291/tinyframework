<?php

namespace bin\Entities;

class AttributeItem
{
    public string $selector;
    public string $name;
    public array $values;

    public function __construct(
        string $selector,
        string $name,
        array $values = []
    ) {
        $this->name = $name;
        $this->selector = $selector;
        $this->values = $values;
    }
}