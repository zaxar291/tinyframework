<?php

namespace bin\Abstraction\Interfaces\WebCore;

interface IErrorPageController
{
    /**
     * @description If development error pages enabled - this one method will be called if almost any error happens into application, since this will look like simple app controller - string result should be returned
     * @param string $error
     * @param int $code
     * @return string
     */
    public function Error(string $error, int $code) : string;
    /**
     * @description If development error pages enabled - this one method will be called if almost any exception happens into application, since this will look like simple app controller - string result should be returned
     * @param \Throwable $throwable
     * @return string
     */
    public function Exception(\Throwable $throwable) : string;
}