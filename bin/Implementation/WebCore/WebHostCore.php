<?php

namespace bin\Implementation\WebCore;

use bin\Abstraction\Helpers\IHttpResponseCodesHelper;
use bin\Abstraction\Interfaces\WebCore\IErrorPageController;
use bin\Abstraction\Interfaces\WebCore\IExtension;
use bin\Abstraction\Interfaces\WebCore\IHostCore;
use bin\Exceptions\WebCore\ExtensionNotExecutable;
use bin\Implementation\Contexts\HttpContext;
use bin\Services\DependencyInjectionService\Implementation\NinjectExecutor;
use Start\bin\Abstraction\Interfaces\Extensions\IExtensions;

class WebHostCore implements IHostCore
{
    private IExtensions $extensions;
    private HttpContext $context;
    private IHttpResponseCodesHelper $httpResponseCodesHelper;
    private IErrorPageController $errorPageController;
    public function __construct(
        IExtensions $extensions,
        IHttpResponseCodesHelper $httpResponseCodesHelper,
        IErrorPageController $errorPageController
    ) {
        $this->extensions = $extensions;
        $this->httpResponseCodesHelper = $httpResponseCodesHelper;
        $this->errorPageController = $errorPageController;
        $this->context = new HttpContext();
    }

    public function Process(): void
    {
        try {
            $extensions = $this->extensions->GetExtensions();
            if ( count( $extensions ) > 0 ) {
                foreach ($extensions as $extension) {
                    $instance = NinjectExecutor::GetInjectExecutor()->Inject($extension["extension"]);
                    if ( !($instance instanceof IExtension)) {
                        throw new ExtensionNotExecutable($extension["extension"] . " can't be executed since it doesn't implement IExtension interface");
                    }
                    $this->context = $instance->Invoke($this->context);
                    if ( $this->context->Rejected() ) {
                        break;
                    }
                }
            }
            if ( trim( $this->context->responseBody ) == "" ) {
                $this->context->responseCode = 204;
            }
            if ( count( $this->context->responseHeaders ) > 0 ) {
                foreach ($this->context->responseHeaders as $responseHeader) {
                    header($responseHeader);
                }
            }
            if ( $this->context->responseCode == 200 ) {
                echo $this->context->responseBody;
            } else {
                $this->httpResponseCodesHelper->SendHttpResponseCode($this->context->responseCode);
            }
        } catch (\Throwable $e) {
            $this->httpResponseCodesHelper->SendHttpResponseCode(500);
            echo $this->errorPageController->Exception($e);
        }
    }
}