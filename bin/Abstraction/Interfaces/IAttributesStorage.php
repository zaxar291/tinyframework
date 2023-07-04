<?php

namespace bin\Abstraction\Interfaces;

use bin\Entities\AttributeItem;
use bin\Entities\ControllerMethodAttribute;

interface IAttributesStorage
{
    /**
     * @param string $attribute
     * @description Add attributes to the storage, be sure $attribute class implement's IAttr interface
     */
    public function AddAttribute(string $attribute) : void;

    /**
     * @param ControllerMethodAttribute[] $attributesList = []
     * @return AttributeItem[]
     * @description Getting the list of the registered attributes
     */
    public function GetAttributes(array $attributesList = []) : array;
}