<?php

namespace bin\Entities;

class AttributeContextItem
{
    public string $className;
    public string $methodName;
    public bool $rejected;
    public bool $next;
    public int $code;

    public function __construct(
        string $className,
        string $methodName,
        bool $rejected = false,
        bool $next = false,
        int $code = 0
    )
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->rejected = $rejected;
        $this->next = $next;
        $this->code = $code;
    }
}