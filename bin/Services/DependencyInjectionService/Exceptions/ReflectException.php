<?php
namespace bin\Services\DependencyInjectionService\Exceptions;


use Throwable;

class ReflectException extends \Exception {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return "System.Reflection - Fatal error occurred: " . $this->message . " <br> Stack trace: " . $this->getTraceAsString();
    }
}