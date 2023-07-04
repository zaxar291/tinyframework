<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\CurrentRouteState;

interface IRouting
{
    public function TryFindHandler() : ?CurrentRouteState;
}