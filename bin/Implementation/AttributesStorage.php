<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IAttributesStorage;
use bin\Entities\AttributeItem;
use bin\Entities\ControllerMethodAttribute;

class AttributesStorage implements IAttributesStorage
{

    private array $attributes;
    private string $attributeSearchKey;

    public function __construct(

    ) {
        $this->attributes = [];
        $this->attributeSearchKey = "Attribute";
    }

    public function AddAttribute(string $attribute): void
    {
        $this->attributes[] = new AttributeItem(
            $this->GetAttributeSelector($attribute),
            $attribute,
            []
        );
    }

    public function GetAttributes(array $attributesList = []): array
    {
        $filteredAttributes = [];
        if ( count( $this->attributes ) > 0 ) {
            foreach ( $this->attributes as $attribute ) {
                $selectedAttribute = array_filter( $attributesList, function (ControllerMethodAttribute $controllerAttribute) use ($attribute) {
                    return $controllerAttribute->attributeName == $attribute->selector;
                } );
                if ( count( $selectedAttribute ) > 0 ) {
                    foreach ($selectedAttribute as $selectAttribute) {
                        $attribute->values = array_merge($attribute->values, $selectAttribute->attributeParams);
                    }
                    $filteredAttributes[] = $attribute;
                }

            }
        }
        return $filteredAttributes;
    }

    private function GetAttributeSelector(string $name) : string {
        return str_ireplace($this->attributeSearchKey, "", $name);
    }
}