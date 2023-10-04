<?php

namespace bin\Attributes;

use bin\Abstraction\Interfaces\IAttribute;
use bin\Abstraction\Interfaces\IRequestBody;
use bin\Implementation\Contexts\HttpContext;

class HttpPostAttribute implements IAttribute
{
    private string $requestType;
    private string $requiredRequestType;
    public function __construct(
        IRequestBody $body
    )
    {
        $this->requestType = $body->GetRequestItem("REQUEST_METHOD")->value;
        $this->requiredRequestType = "POST";
    }
    public function Execute(HttpContext $context): HttpContext
    {
        if ( $this->requestType != $this->requiredRequestType ) {
            return $context->Reject(400);
        }
        return $context;
    }
}