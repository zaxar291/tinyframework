<?php

namespace bin\Middlewares;

use bin\Abstraction\Interfaces\IRoutingStateManager;
use bin\Abstraction\Interfaces\IStorage;
use bin\Abstraction\Interfaces\Middlewares\IMiddleware;
use bin\Entities\Consts;
use bin\Implementation\Contexts\HttpContext;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;

class JsonStreamReflectParserMiddleware implements IMiddleware
{
    use SystemReflection;
    private IRoutingStateManager $routingStateManager;
    private IStorage $storage;
    public function __construct(
        IRoutingStateManager $routingStateManager,
        IStorage $storage
    ) {
        $this->storage = $storage;
        $this->routingStateManager = $routingStateManager;
    }

    public function Invoke(HttpContext $context): HttpContext
    {
        $state = $this->routingStateManager->GetCurrentState();
        if ( !$state->isFound ) return $context->Reject(404);

        $stream = $this->storage->Get(Consts::$StorageStream);
        if ( trim( $stream ) !== "" ) {
            $jsonData = json_decode( $stream );
            if ( json_last_error() !== JSON_ERROR_NONE ) {
                throw new \Exception("Failed to decode request stream, request stream string is: " . $stream);
            }
            $jsonEntities = get_object_vars($jsonData);
            $executableMethod = $this->GetMethod($this->ApplyNamespace($state->controllerName), $state->methodName);
            $methodArgs = $executableMethod->getParameters();
            if ( count( $methodArgs ) > 0 ) {
                foreach ($methodArgs as $methodArg) {
                    $type = $methodArg->getType()->getName();
                    if ( $this->IsNotDefaultDataType($type) ) {
                        if ( class_exists( $type ) ) {
                            $instance = new $type();
                            foreach ($jsonEntities as $key => $value) {
                                if ( property_exists( $instance, $key ) ) {
                                    $instance = $this->ProcessType($instance, $key, $value);
                                }
                            }
                            $this->storage->Set($methodArg->getName(), $instance);
                        }
                    }
                }
            }
        }
        return $context;
    }

    public function ProcessType(object $instance, string $jsonKey, $jsonValue) {
        $property = new \ReflectionProperty($instance, $jsonKey);
        $propertyType = $property->getType()->getName();
        echo $propertyType;
        if ( $propertyType == "string" ) {
            $instance->{$jsonKey} = (string)$jsonValue;
        } else if ($propertyType == "int") {
            $instance->{$jsonKey} = (int)$jsonValue;
        } else if ($propertyType == "bool") {
            $instance->{$jsonKey} = (bool)$jsonValue;
        } else if ($propertyType == "array") {
            $type = $this->SelectArrayType($instance, $jsonKey);
            if ( trim( $type ) !== "" && is_array( $jsonValue ) && count( $jsonValue ) > 0 ) {
                $arrayValues = [];
                foreach ($jsonValue as $key => $value) {
                    $subType = new $type();
                    $entities = get_object_vars($value);
                    if ( count( $entities ) > 0 ) {
                        foreach ( $entities as $entityKey => $entityValue ) {
                            if ( property_exists( $subType, $entityKey ) ) {
                                $arrayValues[] = $this->ProcessType($subType, $entityKey, $entityValue);
                            }
                        }
                    }
                }
                $instance->{$jsonKey} = $arrayValues;
            }
        } else if ($this->IsNotDefaultDataType($propertyType)) {
            if ( class_exists( $propertyType ) ) {
                $type = new $propertyType();
                $entities = get_object_vars($jsonValue);
                if ( count( $entities ) > 0 ) {
                    foreach ($entities as $key => $value) {
                        if ( property_exists( $type, $key ) ) {
                            $type = $this->ProcessType($type, $key, $value);
                        }
                    }
                }
                $instance->{$jsonKey} = $type;
            }
        }
        return $instance;
    }

    private function IsNotDefaultDataType(string $type) : bool {
        return !in_array($type, ["string", "int", "float", "bool", "object", "null", "resource"]);
    }

    private function SelectArrayType(object $instance, string $field) : string {
        $type = "";
        if ( property_exists( $instance, $field ) ) {
            $comments = $this->GetPropertyComments($instance, $field);
            if ( count( $comments ) > 0 ) {
                foreach ($comments as $comment) {
                    if ( stripos( $comment, "@var" ) !== false ) {
                        $typeFromComment = $this->ApplyNamespace(trim(str_ireplace( ["@var", "[]"], "", $comment )));
                        if ( class_exists( $typeFromComment ) ) {
                            $type = $typeFromComment;
                        }
                    }
                }
            }
        }
        return $type;
    }
}