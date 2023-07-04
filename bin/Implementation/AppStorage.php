<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IStorage;

class AppStorage implements IStorage
{

    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function Get(string $key): string
    {
        if ( isset( $this->items[$key] ) ) {
            return $this->items[$key];
        }
        return "";
    }

    public function Set(string $key, string $value)
    {
        $this->items[$key] = $value;
    }

    public function GetAll(): array
    {
        return $this->items;
    }
}