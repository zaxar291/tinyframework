<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IRequestBody;
use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Entities\CurrentRouteState;
use bin\Entities\RequestRouteState;

class RoutingStateManager implements IRoutingStateManager
{
    private string $baseRouteState = "#core";
    private string $requestUrlStringItem = "REQUEST_URI";
    private CurrentRouteState $currentRouteState;
    private RequestRouteState $requestRouteState;
    private array $allStates;

    public function __construct(
        IRequestBody $requestBody
    ) {
        $this->currentRouteState = new CurrentRouteState($requestBody->GetRequestItem($this->requestUrlStringItem)->value, $this->baseRouteState);
        $this->requestRouteState = new RequestRouteState($requestBody->GetRequestItem($this->requestUrlStringItem)->value, $this->baseRouteState);
        $this->allStates = [];
    }

    public function UpdateState(CurrentRouteState $newState)
    {
        $this->allStates[] = $newState;
        $this->currentRouteState = $newState;
    }

    public function GetCurrentState(): CurrentRouteState
    {
        return $this->currentRouteState;
    }

    public function GetRequestState(): RequestRouteState
    {
        return $this->requestRouteState;
    }

    public function Reject(): void
    {
        $this->currentRouteState->isRejected = true;
    }

    public function Redirect(string $url): void
    {
        $this->currentRouteState->redirectPath = $url;
        $this->currentRouteState->isFound = true;
    }

    public function Next(): void
    {
        $this->currentRouteState->isFound = false;
    }

    public function Break(): void
    {
        $this->currentRouteState->isFound = true;
    }
}