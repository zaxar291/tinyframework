<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IRequestBody;
use bin\Abstraction\Interfaces\IStorage;
use bin\Entities\RequestBodyEntity;

class RequestBody implements IRequestBody
{

    private array $requestBodyItems;

    private IStorage $storage;
    public function __construct(
        IStorage $storage
    ) {
        $this->requestBodyItems = [];
        $this->storage = $storage;
        $this->ReadAndWriteAllRequestItems();
    }

    public function GetAllRequestItems(): array
    {
        return $this->requestBodyItems;
    }

    public function GetRequestItem(string $key): ?RequestBodyEntity
    {
        $filtered = array_filter($this->requestBodyItems, function($item) use ($key){
            return $item->key == $key;
        });
        if (count($filtered) > 0) {
            return $filtered[array_key_first($filtered)];
        }
        return null;
    }

    public function ReadAndWriteAllRequestItems() {
        $items = $_SERVER;
        if ( is_array( $items ) && count( $items ) > 0 ) {
            foreach ($items as $key => $value) {
                $this->requestBodyItems[] = new RequestBodyEntity($key, $value);
                $this->storage->Set($key, $value);
            }
        }
    }

}