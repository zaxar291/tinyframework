<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\ControllerItem;

interface IRoutingHelper
{
    public function SelectControllerHandlerMethod(ControllerItem $controllerItem) : string;
}