<?php

namespace bin\startup;

class BootConfiguration
{
    /**
     * @var string
     * @description sets base dir for loading deps
     */
    public string $baseDir;

    /**
     * @var string
     * @description sets base core dir for loading deps
     */
    public string $appCoreDir;

    /**
     * @var string
     * @description sets base dir for web app
     */
    public string $webAppDir;

    /**
     * @var string
     * @description sets dir for compressed cache
     */
    public string $compressedCacheFolder;

    /**
     * @var string
     * @description sets dir name for compressed cache
     */
    public string $compressedCacheFolderName;

    /**
     * @var Environment
     * @description sets environment for
     */
    public Environment $environment;

    /**
     * @var string
     * @description minimum php version
     */
    public string $minPhpVersion;

    public function __construct(
        string $baseDir,
        string $appCoreDir,
        string $webAppDir,
        string $compressedCacheFolder,
        string $compressedCacheFolderName,
        string $minPhpVersion
    ) {
        $this->baseDir = $baseDir;
        $this->appCoreDir = $appCoreDir;
        $this->webAppDir = $webAppDir;
        $this->compressedCacheFolder = $compressedCacheFolder;
        $this->compressedCacheFolderName = $compressedCacheFolderName;
        $this->minPhpVersion = $minPhpVersion;
    }
}