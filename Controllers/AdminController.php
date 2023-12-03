<?php

namespace Controllers;

use bin\Abstraction\Classes\Controller;

class AdminController extends Controller
{
    public function Get() {
        return $this->View("index", "Admin");
    }
}