<?php

namespace bin\Abstraction\Interfaces;

interface IHostBuilder {
    static function BuildHost(array $args) : self;
    public function UseStartup(string $startup) : self;
    public function Start() : void;
}