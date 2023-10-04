<?php

namespace bin\Abstraction\Interfaces\WebCore;

interface IHost
{
    /**
     * @description Creating host builder
     * @param array $args
     * @return IHostBuilder
     */
    public static function CreateBuilder(array $args = []) : IHostBuilder;
}