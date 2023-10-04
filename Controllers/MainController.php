<?php
namespace Controllers;

use bin\Abstraction\Classes\Controller;

class MainController extends Controller
{
    public function __construct(
    ) {
    }
    /**
     * [HttpGet("true")]
    */
    public function Get() : string {
        return $this->View("index", " This is main page handler");
    }
    public function Post() : string {
        echo "dqwdqw";die;
    }
}
