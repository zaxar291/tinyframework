<?php

namespace bin\Services\DependencyInjectionService\Entities;

class InjectExtraParam
{
    public string $type;
    public string $name;
    public $value;

    public function __construct(
        string $type,
        string $name,
        $value
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }
}