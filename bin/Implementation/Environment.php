<?php

namespace bin\Implementation;

use bin\Abstraction\Interfaces\IEnvironment;
use stdClass;

class Environment implements IEnvironment
{
    private string $environmentSource;
    private array $envData;
    private int $envState;
    private array $sections;

    public function __construct() {
        $this->environmentSource = "";
        $this->envData = [];
        $this->sections = [];
        $this->envState = JSON_ERROR_NONE;
        $this->LoadEnvironmentData();
    }
    public function Get(string $key)
    {
        return $this->envData[$key] ?? null;
    }

    public function IsDevelopment() : bool {
        return $this->Get("env") == "dev" || $this->Get("env") == "development";
    }

    public function ToArray(): array
    {
        return $this->envState == JSON_ERROR_NONE ? $this->envData : [];
    }

    public function ToObject(): object
    {
        return $this->envState == JSON_ERROR_NONE ? json_decode($this->environmentSource) : new stdClass();
    }

    public function CreateSection(string $sectionName, $data): void
    {
        $this->sections[$sectionName] = $data;
    }

    public function GetSection(string $sectionName)
    {
        return $this->sections[$sectionName] ?? null;
    }

    private function LoadEnvironmentData() : void {
        if (!file_exists( ROOT . "appsettings.json" )) return;
        $data = file_get_contents(ROOT . "/appsettings.json");
        if ( trim( $data ) !== "" ) {
            $json = json_decode($data, true);
            $this->envState = json_last_error();
            if ( json_last_error() == JSON_ERROR_NONE ) {
                $this->environmentSource = $data;
                $this->envData = $json;
            }
        }
    }
}