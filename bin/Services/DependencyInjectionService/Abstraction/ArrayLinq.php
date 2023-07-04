<?php

namespace bin\Services\DependencyInjectionService\Abstraction;

abstract class ArrayLinq {

    public function Select(array $array, callable $filter) : ?object {
        $selection = $this->Filter($array, $filter);
        if (is_array($selection) && count($selection) > 0) {
            return $selection[array_key_first($selection)];
        }
        return null;
    }

    public function Filter(array $array, callable $filter) : array {
        return array_filter($array, $filter);
    }

    public function Last(array $array) : ?object {
        return count($array) > 0 ? $array[count($array) - 1] : null;
    }
}