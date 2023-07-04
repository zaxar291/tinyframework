<?php

namespace bin\Entities;

class Header
{
    public string $key;
    public string $value;
    public function __construct(
        string $key,
        string $value
    ) {
        $this->key = $key;
        $this->value = $value;
    }
}