<?php

namespace Start\bin\Implementation\Storages;

use bin\Abstraction\Interfaces\IStorage;

class AppStorage implements IStorage
{

    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function Get(string $key)
    {
        if ( isset( $this->items[$key] ) ) {
            return $this->items[$key];
        }
        return "";
    }

    public function Set(string $key, $value): void
    {
        $this->items[$key] = $value;
    }

    public function GetAll(): array
    {
        return $this->items;
    }
}