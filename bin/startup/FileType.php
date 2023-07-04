<?php

namespace bin\startup;

class FileType
{
    /**
     * @var string
     * @description absolute path to the file specified
     */
    public string $absolutePath;

    /**
     * @var string
     * @description full file name
     */
    public string $fileName;

    /**
     * @var FileType[]
     * @description files to be loaded before this one
     */
    public array $requiredFiles;

    /**
     * @var string
     * @description declare what's file contains
     */
    public string $fileType;

    /**
     * @var bool
     * @description determine is file loaded
     */
    public bool $isLoaded;

    public function __construct(
        string $absolutePath,
        string $fileName = "",
        array $requiredFiles = [],
        string $fileType = ""
    ) {
        $this->absolutePath = $absolutePath;
        $this->fileName = $fileName;
        $this->requiredFiles = $requiredFiles;
        $this->fileType = $fileType;
        $this->isLoaded = false;
    }
}