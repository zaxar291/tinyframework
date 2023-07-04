<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\Header;

interface IHeaders
{
    public function GetAllHeaders() : array;
    public function GetHeader(string $key) : ?Header;
}