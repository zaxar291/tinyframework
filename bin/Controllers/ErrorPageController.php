<?php

namespace bin\Controllers;
use bin\Abstraction\Interfaces\WebCore\IErrorPageController;

class ErrorPageController implements IErrorPageController
{

    public function Error(string $error, int $code): string
    {
        return $error;
    }

    public function Exception(\Throwable $throwable): string
    {
        $result = "";
        ob_start();
        $model = $throwable;
        require 'bin/Views/Exception.php';
        $result = ob_get_clean();
        ob_end_clean();
        return $result;
    }
}