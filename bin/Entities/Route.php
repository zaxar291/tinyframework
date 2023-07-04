<?php

namespace bin\Entities;

class Route
{
    public string $name;
    public string $pattern;
    public function __construct(
        string $name,
        string $pattern
    ) {
        $this->name = $name;
        $this->pattern = $pattern;
    }
}