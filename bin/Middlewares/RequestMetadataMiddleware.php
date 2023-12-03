<?php

namespace bin\Middlewares;

use bin\Abstraction\Interfaces\IStorage;
use bin\Abstraction\Interfaces\Middlewares\IMiddleware;
use bin\Entities\Consts;
use bin\Implementation\Contexts\HttpContext;

class RequestMetadataMiddleware implements IMiddleware
{

    private IStorage $storage;

    public function __construct(
        IStorage $storage
    ) {
        $this->storage = $storage;
    }

    public function Invoke(HttpContext $context): HttpContext
    {
        $method = $this->RequestMethod();
        $schema = $this->RequestSchema();
        $type = $this->RequestType();
        $stream = $this->GetAndMapRequestBody();

        if ( is_null( $method ) && !is_null( $schema ) && !is_null( $type ) ) return $context->Reject(400);

        $context->requestMethod = $method;
        $context->requestSchema = $schema;
        $context->requestType = $type;
        $context->requestStream = $stream;

        return $context;
    }

    private function RequestMethod() : ?string {
        if ( !isset( $_SERVER ) || count( $_SERVER ) == 0) return null;

        return $_SERVER["REQUEST_METHOD"];
    }

    private function RequestSchema() : ?string {
        if ( !isset( $_SERVER ) || count( $_SERVER ) == 0) return null;

        return $_SERVER["REQUEST_SCHEME"];
    }

    private function RequestType() : ?string {
        if ( !isset( $_SERVER ) || count( $_SERVER ) == 0) return null;
        if ( isset( $_SERVER["HTTP_CONTENTTYPE"] ) ) {
            return $_SERVER["HTTP_CONTENTTYPE"];
        }
        if ( isset( $_SERVER["HTTP_ACCEPT"] ) ) {
            return $_SERVER["HTTP_ACCEPT"];
        }
        return "*/*";
    }

    public function GetAndMapRequestBody() : string {
        if ( is_array( $_REQUEST ) && count( $_REQUEST ) > 0 ) {
            foreach ($_REQUEST as $key => $value) {
                $this->storage->Set($key, $this->ParseValueByType($value));
            }
        }
        if ( !isset( $_REQUEST ) || count( $_REQUEST ) == 0 ) {
            $inputRequest = file_get_contents("php://input");
            if ( trim( $inputRequest ) !== "" ) {
                $data = json_decode($inputRequest, true);
                $this->storage->Set(Consts::$StorageStream, $inputRequest);
                if ( JSON_ERROR_NONE == json_last_error() ) {
                    foreach ($data as $key => $value) {
                        $this->storage->Set($key, $value);
                    }
                }
                return $inputRequest;
            }
        }
        $this->storage->Set("app.storage.mappings", json_encode([["GET", "Get"], ["GET", "Index"], ["POST", "Post"]]));
        return "";
    }

    public function ParseValueByType($value) {
        if ( $value == "true" || $value == "false" ) {
            return $value == "true";
        }

        return $value;
    }
}