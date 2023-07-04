<?php

namespace Controllers;

use bin\Abstraction\Classes\Controller;

class TestController extends Controller
{
    public function __construct(

    ) {

    }

    /**
     * [HttpGet]
     */
    public function Get() : string {
        return $this->View("index", "This is test get");
    }
    public function Post() : string {
        return $this->View("index", "posst");
    }
    public function Test() : string {
        return $this->View("index", "This is test get for test url");
    }

    /**
     * [Route("{lang?}/test/{id}")]
     */
    public function OneNews(int $id) : string {
        return $this->View("index", "This page with id " . $id);
    }
}
