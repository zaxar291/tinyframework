<?php

namespace bin\Attributes;

use bin\Abstraction\Interfaces\IAttribute;
use bin\Abstraction\Interfaces\IAttributeContext;
use bin\Abstraction\Interfaces\IRequestBody;

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

    public function Execute(IAttributeContext $context): IAttributeContext
    {
        if ( $this->requestType == $this->requiredRequestType ) {
            return $context->Next();
        }
        return $context->Reject();
    }

}
