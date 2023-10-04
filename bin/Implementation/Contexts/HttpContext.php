<?php

namespace bin\Implementation\Contexts;
use bin\Abstraction\Classes\Context;

class HttpContext extends Context
{
    /**
     * @description Request schema (e.g http, https)
     */
    public string $requestSchema;
    private bool $rejected;

    public function __construct(
        string $requestSchema = "",
        string $requestType = "",
        string $requestStream = "",
        bool $rejected = false
    ) {
        $this->requestSchema = $requestSchema;
        $this->requestType = $requestType;
        $this->requestStream = $requestStream;
        $this->rejected = $rejected;
    }

    /**
     * @description Use this method if you want to reject request processing and send any error page
     * @param int $code - reject code for request
     * @return HttpContext
     */
    public function Reject(int $code) : HttpContext {
        $this->responseCode = $code;
        $this->rejected = true;
        return $this;
    }

    /**
     * @description After each middleware step Middleware extension will call this method to define if request should be rejected and passed to the error page displaying
     */
    public function Rejected() : bool {
        return $this->rejected;
    }

}