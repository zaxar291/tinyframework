<?php

namespace bin\Services\DependencyInjectionService\Implementation;

use bin\Services\DependencyInjectionService\Abstraction\BaseInject;
use bin\Services\DependencyInjectionService\Abstraction\InjectAllocation;
use bin\Services\DependencyInjectionService\Abstraction\InjectBindable;
use bin\Services\DependencyInjectionService\Abstraction\InjectBinder;

class Ninject extends InjectBinder {

    public static function Bind(string $bind) : InjectBindable {
        BaseInject::GetInjectExecutor()->Bind($bind);
        $allocation = new InjectAllocation();
        $className = $allocation->Allocate("bin\Services\DependencyInjectionService\Abstraction\InjectBindable");

        if ($className !== null) {
            return new $className();
        }
        throw new \Exception("Fatal error: no classes that implemented InjectBindable!");
    }
}