<?php

namespace Controllers;

use bin\Abstraction\Classes\Controller;


class MainController extends Controller
{

    public function __construct(
    ) {
    }

    public function Get(string $lang) : string {
        return $this->View("index", "{$lang} This is main page handler");
    }

}