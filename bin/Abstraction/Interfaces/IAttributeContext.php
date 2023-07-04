<?php

namespace bin\Abstraction\Interfaces;

interface IAttributeContext
{
    /**
     * @description Get the class name which one will handle request
     */
    public function GetClass() : string;

    /**
     * @description Get the class method which one will handle request
     */
    public function GetMethod() : string;

    /**
     * @description  Call this method if everything went fine in your attribute so other attributes will be launched
     */
    public function Next() : self;

    /**
     * @param int $code
     * @description Call this method if you want to reject request and navigate user to any error page
     */
    public function Reject(int $code = 0) : self;
    /**
     * @description Check if attribute rejected request (actually this will be used only in IAttributesParser)
     */
    public function Rejected() : bool;

}