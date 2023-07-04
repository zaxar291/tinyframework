<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\CurrentRouteState;
use bin\Entities\RequestRouteState;

interface IRoutingStateManager
{
    public function UpdateState(CurrentRouteState $newState);
    public function GetCurrentState() : CurrentRouteState;
    public function GetRequestState() : RequestRouteState;
    public function Reject() : void;
    public function Redirect(string $url) : void;
    public function Next() : void;
    public function Break() : void;
}