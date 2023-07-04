<?php

namespace bin\Services\DependencyInjectionService\Abstraction;

abstract class InjectBindable extends InjectAllocation {
    public abstract function To( string $bindable ) : InjectScopeBuilder;
}