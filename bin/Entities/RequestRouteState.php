<?php

namespace bin\Entities;

class RequestRouteState
{
    public string $provider;
    public string $url;
    public bool $isRejected;
    public bool $isFound;
    public string $redirectPath;
    public string $controllerName;
    public string $methodName;
    public function __construct(
        string $url,
        string $provider,
        bool $isFound = false,
        string $controllerName = "",
        string $methodName = ""
    ) {
        $this->url = $url;
        $this->provider = $provider;
        $this->isRejected = false;
        $this->isFound = $isFound;
        $this->redirectPath = "";
        $this->controllerName = $controllerName;
        $this->methodName = $methodName;
    }
}