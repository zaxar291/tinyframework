<?php

namespace bin\Attributes;

use bin\Abstraction\Interfaces\IAttribute;
use bin\Abstraction\Interfaces\IAttributeContext;
use bin\Abstraction\Interfaces\IRequestBody;
use bin\Implementation\Contexts\HttpContext;

class HttpGetAttribute implements IAttribute
{
    private string $requestType;
    private string $requiredRequestType;
    public function __construct(
        IRequestBody $body
    )
    {
        $this->requestType = $body->GetRequestItem("REQUEST_METHOD")->value;
        $this->requiredRequestType = "GET";
    }
    public function Execute(HttpContext $context): HttpContext
    {
        if ( $this->requestType != $this->requiredRequestType ) {
            return $context->Reject(400);
        }
        return $context;
    }

}
