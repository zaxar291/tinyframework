<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IAttributeParser;
use bin\Entities\ControllerMethodAttribute;
use bin\Entities\ControllerMethodAttributeParam;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;

class AttributeParser implements IAttributeParser
{
    use SystemReflection;
    private array $parsedAttributes;
    public function __construct(
    ) {
        $this->parsedAttributes = [];
    }
    public function ParseAttributes(string $className, string $methodName): array
    {
        if ( isset( $this->parsedAttributes[ $this->ApplyRequestHash( $className, $methodName ) ] ) ) {
            return $this->parsedAttributes[ $this->ApplyRequestHash( $className, $methodName ) ];
        }
        $allComments = $this->GetCommentsAsArray($className, $methodName);
        $attributes = [];
        if ( count( $allComments ) > 0 ) {
            foreach ( $allComments as $comment ) {
                if ( $this->IsAttributeComment( $comment ) ) {
                    $attributes[] = new ControllerMethodAttribute(
                        $this->GetAttributeName( $comment ),
                        $this->GetAttributeParams( $comment )
                    );
                }
            }
            $this->parsedAttributes[ $this->ApplyRequestHash( $className, $methodName ) ] = $attributes;
        }
        return $attributes;
    }

    private function IsAttributeComment(string $comment) : bool {
        return stripos( $comment, "[" ) !== false && stripos( $comment, "]" ) !== false;
    }

    private function GetAttributeName(string $attributeName) : string {
        if ( preg_match( "/[A-Za-z0-9_]+/", $attributeName, $matches ) ) {
            if ( is_array( $matches ) && count( $matches ) > 0 ) {
                return $matches[array_key_first( $matches )];
            }
        }
        return "";
    }

    private function GetAttributeParams(string $attributeName) : array {
        $allParams = "";
        $attributeParameters = [];
        if ( preg_match( "/\(.+\)/", $attributeName, $allParams ) ) {
            if ( is_array( $allParams ) && isset( $allParams[array_key_first( $allParams )] ) ) {
                $paramList = explode( ",", str_ireplace( ["(", ")", '"'], "", $allParams[array_key_first( $allParams )] ) );
                if ( count ( $paramList ) > 0 ) {
                    foreach ($paramList as $param) {
                        $attributeParameters[] = new ControllerMethodAttributeParam($param);
                    }
                }
            }
        }
        return $attributeParameters;
    }

    private function ApplyRequestHash(string $className, string $methodName) : string {
        return md5($className.$methodName);
    }
}