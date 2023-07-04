<?php

namespace bin\Services\DependencyInjectionService\Abstraction;

abstract class InjectScopeBuilder {
    public abstract function InTransientScope() : void;
    public abstract function InRequestScope() : void;
}