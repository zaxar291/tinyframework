<?php

namespace bin\Implementation\Extensions;

use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Abstraction\Interfaces\IStorage;
use bin\Abstraction\Interfaces\WebCore\IExtension;
use bin\Implementation\Contexts\HttpContext;
use bin\Services\DependencyInjectionService\Entities\InjectExtraParam;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;

class MvcTemplateExtension implements IExtension
{
    private IRoutingStateManager $routingStateManager;
    private IStorage $storage;
    public function __construct(
        IRoutingStateManager $routingStateManager,
        IStorage $storage
    )
    {
        $this->routingStateManager = $routingStateManager;
        $this->storage = $storage;
    }

    public function Invoke(HttpContext $context): HttpContext
    {
        $state = $this->routingStateManager->GetCurrentState();
        if ( $state->methodName == "" || $state->controllerName == "" ) return $context->Reject(404);

        $result = NinjectExecutor::GetInjectExecutor()->Inject($state->controllerName, $state->methodName, $this->StorageParamsToNinjectEntity());

        if ( trim( $result ) !== "" ) {
            $context->responseBody = $result;
            $context->responseType = $this->GetResponseContentType($result);
        }

        return $context;
    }

    private function StorageParamsToNinjectEntity() : array {
        $params = [];
        foreach ( $this->storage->GetAll() as $storageKey => $storageValue ) {
            if ( gettype( $storageValue ) == "object" ) {
                $params[] = new InjectExtraParam(get_class($storageValue), $storageKey, $storageValue );
            } else {
                $params[] = new InjectExtraParam(gettype( $storageValue ), $storageKey, $storageValue );
            }
        }
        return $params;
    }

    private function GetResponseContentType(string $body) : string {
        if ( $this->IsJson( $body ) ) return "application/json";
        return "text/html";
    }

    private function IsJson(string $stream) : bool {
        json_decode($stream);
        return json_last_error() == JSON_ERROR_NONE;
    }
}