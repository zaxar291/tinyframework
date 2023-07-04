<?php

namespace bin\Services\DependencyInjectionService\Implementation;

use bin\Services\DependencyInjectionService\Abstraction\BaseInject;
use bin\Services\DependencyInjectionService\Abstraction\InjectScopeBuilder;

class NinjectScope extends InjectScopeBuilder {

    public function InTransientScope() : void {
        BaseInject::GetInjectExecutor()->InTransientScope();
    }

    public function InRequestScope() : void {
        BaseInject::GetInjectExecutor()->InRequestScope();
    }
}