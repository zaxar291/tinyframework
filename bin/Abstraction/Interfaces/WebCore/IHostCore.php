<?php

namespace bin\Abstraction\Interfaces\WebCore;

interface IHostCore
{
    /**
     * @description Launch request processing via realised class
     */
    public function Process() : void;
}