<?php

namespace bin\Entities;

class RouteMinMaxLength
{
    public int $minLength;
    public int $maxLength;

    public function __construct(
        int $minLength = 0,
        int $maxLength = 0
    ) {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }
}