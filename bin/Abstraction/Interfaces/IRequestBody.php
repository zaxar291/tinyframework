<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\RequestBodyEntity;

interface IRequestBody
{
    public function GetAllRequestItems() : array;
    public function GetRequestItem(string $key) : ?RequestBodyEntity;
}