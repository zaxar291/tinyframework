<?php
namespace bin\startup;

class HostLoader
{
    /**
     * @var BootConfiguration
     * @description provide fool boot configuration
     */
    private BootConfiguration $configuration;

    /**
     * @var FileType[]
     * @description full list of core files
     */
    private array $listAppCoreFiles;

    /**
     * @var FileType[]
     * @description parsed list of the dependencies
     */
    private array $listParsedFiles;

    /**
     * @var FileTypeParser
     * @description class to parse file information
     */
    private FileTypeParser $fileTypeParser;

    /**
     * @var FileType[]
     * @description list of included files to prevent double including
     */
    private array $listIncludedFiles;

    /**
     * @var FileType[]
     * @description queued list of included files
     */
    private array $queue;

    public function __construct(
        BootConfiguration $configuration
    ) {
        $this->configuration = $configuration;
        $this->ValidateConfiguration();
        $this->fileTypeParser = new FileTypeParser();
        $this->queue = [];
        $this->listAppCoreFiles = [];
        $this->listParsedFiles = [];
    }

    private function ValidateConfiguration() : void {
        if (
            !is_dir( $this->configuration->baseDir ) ||
            !is_dir( $this->configuration->appCoreDir ) ||
            !is_dir( $this->configuration->webAppDir ) ||
            $this->configuration->compressedCacheFolderName == "" ||
            !version_compare( $this->configuration->minPhpVersion, phpversion(), "<=" )
        ) {
            die("Fatal: not enough arguments provided");
        }
    }

    public function InitApplication() : void {
        $this->ListAllFiles();
    }

    private function ListAllFiles() : void {
        $this->listAppCoreFiles(
            $this->configuration->appCoreDir
        );
        $this->fileTypeParser->SetDependenciesList(
            $this->listAppCoreFiles
        );
        $this->ParseDetectedDependencies();
        $this->CreateQueue(
            $this->listParsedFiles
        );
        $this->IncludeListedDependencies();
    }

    private function ListAppCoreFiles(string $d) : void {
        if ( is_dir( $d ) ) {
            $l = array_diff( scandir( $d ), [".", "..", "startup", "index.php", "Data", "Views", "templates_c"] );
            if ( count( $l ) > 0 ) {
                foreach ($l as $i) {
                    if ( is_file( $d . $i ) && stripos($i, ".php") !== false ) {
                        $this->listAppCoreFiles[] = $this->PrepareFileType(
                            $d . $i
                        );
                    } else {
                        $this->ListAppCoreFiles( $d . $i . "/" );
                    }
                }
            }
        }
    }

    private function PrepareFileType(string $f) : FileType {
        return new FileType(
            $f,
            basename($f)
        );
    }

    private function ParseDetectedDependencies() : void {
        $depList = array_merge( $this->listAppCoreFiles, [] );
        foreach ($depList as $k => $d) {
            $this->listParsedFiles[] = $this->fileTypeParser->ParseFileInfo($d);

        }
    }

    private function CreateQueue(array $dependenciesList) : void {
        foreach ($dependenciesList as $file) {
            if ( count( $file->requiredFiles ) > 0 ) {
                $this->CreateQueue( $file->requiredFiles );
            }
            if ( !$this->IsInQueue( $file ) ) {
                $this->queue[] = $file;
            }
        }
    }

    private function IsInQueue(FileType $f) : bool {
        $s = array_filter( $this->queue, function($d) use ($f) {
            return $d->fileName == $f->fileName && $d->absolutePath == $f->absolutePath;
        } );

        return count( $s ) > 0;
    }

    private function IncludeListedDependencies() : void {
        foreach ($this->queue as $file) {
            if ( $file->fileType !== FileEntityType::$undefined ) {
                require $file->absolutePath;
            }
        }
    }
}