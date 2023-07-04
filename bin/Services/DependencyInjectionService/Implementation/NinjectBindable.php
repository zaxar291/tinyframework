<?php

namespace bin\Services\DependencyInjectionService\Implementation;

use bin\Services\DependencyInjectionService\Abstraction\BaseInject;
use bin\Services\DependencyInjectionService\Abstraction\InjectBindable;
use bin\Services\DependencyInjectionService\Abstraction\InjectScopeBuilder;

class NinjectBindable extends InjectBindable {

    public function To(string $bindable) : InjectScopeBuilder {
        BaseInject::GetInjectExecutor()->To($bindable);
        $className = $this->Allocate("bin\Services\DependencyInjectionService\Abstraction\InjectScopeBuilder");
        if ($className != null) {
            return new $className();
        }
        throw new \Exception("Fatal error: no classes that implemented InjectScopeBuilder!");
    }
}