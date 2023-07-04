<?php

namespace bin\Services\DependencyInjectionService\Abstraction;

class InjectAllocation extends ArrayLinq {

    public function Allocate(string $allocation, string $allocationNamespace = "Services\DependencyInjectionService\Implementation\\") : ?string {
        $classList = $this->Filter(get_declared_classes(), function($c) use ($allocationNamespace) {
            return trim($allocationNamespace == "") || strpos($c, $allocationNamespace) !== false;
        });

        if (!is_array( $classList )) {
            return null;
        }
        foreach ($classList as $class) {
            if (is_subclass_of($class, $allocation)) {
                return $class;
            }
        }
        return null;
    }

}