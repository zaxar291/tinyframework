<?php

namespace bin\Services\DependencyInjectionService\Abstraction;

abstract class InjectBinder {
    public abstract static function Bind( string $bind ) : InjectBindable;
}
