<?php

namespace Start\bin\Implementation\Storages;

use bin\Abstraction\Interfaces\IRequestHeader;
use bin\Abstraction\Interfaces\IStorage;
use bin\Entities\Header;

class RequestHeaders implements IRequestHeader
{

    /**
     * @var Header[] $headers
     */
    private array $headers;

    private IStorage $storage;
    public function __construct(
        IStorage $storage
    ) {
        $this->headers = [];
        $this->storage = $storage;
        $this->ReadAndWriteAllRequestHeaders();
    }

    public function GetAllHeaders(): array
    {
        return $this->headers;
    }

    public function GetHeader(string $key): ?Header
    {
        $filtered = array_filter($this->headers, function($header) use ($key){
            return $header->key == $key;
        });
        if (count($filtered) > 0) {
            return $filtered[0];
        }
        return null;
    }

    private function ReadAndWriteAllRequestHeaders() {
        $headers = getallheaders();
        if ( is_array( $headers ) && count( $headers ) > 0 ) {
            foreach ( $headers as $key => $value ) {
                $this->headers[] = new Header($key, $value);
                $this->storage->Set($key, $value);
            }
        }
    }
}