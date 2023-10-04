<?php

namespace bin\Implementation\Extensions;
use Start\bin\Abstraction\Interfaces\Extensions\IExtensions;

class Extensions implements IExtensions
{
    private array $extensions;

    public function __construct() {
        $this->extensions = [];
    }

    public function AddExtension(string $extension, int $prior): void
    {
        $this->extensions[] = [
            "extension" => $extension,
            "prior" => $prior
        ];
    }

    public function GetExtensions(): array
    {
        $temp = $this->extensions;
        uasort($temp, function($a, $b) {
            if ( $a["prior"] < $b["prior"] ) return -1;
            elseif ($a["prior"] == $b["prior"]) return 0;
            else return 1;
        });
        return $temp;
    }
}