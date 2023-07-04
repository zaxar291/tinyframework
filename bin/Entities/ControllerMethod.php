<?php

namespace bin\Entities;

class ControllerMethod
{
    public string $methodName;
    /**
     * @param $comments ControllerMethodAttribute[]
     */
    public array $comments;

    public function __construct(
        string $methodName,
        array $comments
    )
    {
        $this->methodName = $methodName;
        $this->comments = $comments;
    }
}