<?php

namespace bin\Services\DependencyInjectionService\Entities;

class InjectEntity {
    public $bindable;
    public ?string $binder;
    public ?string $scope;

    public ?object $instance;
    public bool $isCustomParam;
    public string $customParamValue;

    public function __construct(
        string $binder,
        bool $isCustomParam = false
    ) {
        $this->binder = $binder;
        $this->isCustomParam = $isCustomParam;
        $this->instance = null;
        $this->customParamValue = "";
    }
}