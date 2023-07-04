<?php

namespace bin\Abstraction\Interfaces;

use bin\Abstraction\Classes\BaseStartup;

interface IRequestsListenerCore
{
    public function ApplyStartup(BaseStartup $startup) : self;
    public function HandleIncomeRequest() : void;
}