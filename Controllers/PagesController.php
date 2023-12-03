<?php

namespace Controllers;

use bin\Abstraction\Classes\Controller;

class PagesController extends Controller
{
    public function Get() : string {
        return $this->View("index", "Pages");
    }
    public function Post() : string {

        return $this->View("index", "Post response");
    }
    public function Patch() : string {

        return $this->View("index", "Patch response");
    }
    public function List() : string {

        return $this->View("index", "List");
    }
}