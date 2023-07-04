<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IAttributeContext;
use bin\Entities\AttributeContextItem;

class AttributeContext implements IAttributeContext
{

    private AttributeContextItem $attributeContextItem;

    public function __construct(
        string $className,
        string $methodName
    ) {
        $this->attributeContextItem = new AttributeContextItem($className, $methodName);
    }

    public function GetClass() : string
    {
        return $this->attributeContextItem->className;
    }

    public function GetMethod() : string
    {
        return $this->attributeContextItem->methodName;
    }

    public function Next() : self
    {
        $this->attributeContextItem->next = true;
        return $this;
    }

    public function Reject(int $code = 0) : self
    {
        $this->attributeContextItem->rejected = true;
        $this->attributeContextItem->code = $code;
        return $this;
    }

    public function Rejected() : bool
    {
        return $this->attributeContextItem->rejected;
    }
}