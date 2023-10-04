<?php

namespace bin\Implementation\Extensions;

use bin\Abstraction\Interfaces\Middlewares\IMiddleware;
use bin\Abstraction\Interfaces\Middlewares\IMiddlewareStorage;
use bin\Abstraction\Interfaces\WebCore\IExtension;
use bin\Implementation\Contexts\HttpContext;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class MiddlewareExtension implements IExtension
{
    private IMiddlewareStorage $middlewareStorage;
    public function __construct(
        IMiddlewareStorage $middlewareStorage
    ) {
        $this->middlewareStorage = $middlewareStorage;
    }

    public function Invoke(HttpContext $context): HttpContext
    {
        $middlewares = $this->middlewareStorage->GetAll();
        if ( count( $middlewares ) > 0 ) {
            foreach ($middlewares as $middleware) {
                $instance = NinjectExecutor::GetInjectExecutor()->Inject($middleware);
                if ( $instance instanceof IMiddleware) {
                    $context = $instance->Invoke($context);
                    if ( $context->Rejected() ) {
                        return $context;
                    }
                }
            }
        }
        return $context;
    }
}