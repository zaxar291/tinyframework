<?php

namespace bin\startup;

class FileTypeParser
{
    /**
     * @var array
     * @description full dependencies list for checking if required dependency exists
     */
    private array $depList;

    /**
     * @var string
     * @description parsed file content
     */
    private string $fileContent;

    public function __construct(
        array $depList = []
    ) {
        $this->depList = $depList;
    }

    public function SetDependenciesList(array $depList) : void {
        $this->depList = $depList;
    }

    public function ParseFileInfo(FileType $f) : FileType {
        $this->ParseFile($f->absolutePath);
        $f->fileType = $this->ParseFileType();
        $f->requiredFiles = $this->ParseFileDependencies();
        return $f;
    }

    private function ParseFileDependencies() : array {
        preg_match("/extends [A-Za-z0-9 ]+/", $this->fileContent, $extendedClasses);
        preg_match("/implements [A-Za-z0-9, ]+/", $this->fileContent, $interfacesList);
        preg_match("/use [A-Za-z\d,? ]+;/", $this->fileContent, $traitsList);
        return array_merge(
            $this->ProcessClass( $extendedClasses ),
            $this->ProcessInterfaces( $interfacesList ),
            $this->ProcessTraits( $traitsList )
        );
    }

    private function ProcessClass(array $c) : array {
        if ( count( $c ) == 0 ) return [];
        $className = trim(str_ireplace("extends ", "", $c[0]));
        $class = $this->SelectEntityByName( $className );
        return $this->EntityFilter( [ $class ] );
    }

    private function ProcessInterfaces( array $i ) : array {
        if ( count( $i ) == 0 ) return [];
        $formattedList = explode( ",", str_ireplace( "implements ", "", $i[0] ) );
        $interfacesList = [];
        foreach ( $formattedList as $interface ) {
            $interfacesList[] = $this->SelectEntityByName( trim( $interface ) );
        }

        return $this->EntityFilter( $interfacesList );
    }

    private function ProcessTraits( array $t ) : array {
        if ( count( $t ) == 0 ) return [];
        $formattedList = explode( ",", str_ireplace( ["use ", ";"], ["", ""], $t[0] ) );
        $traitsList = [];
        foreach ( $formattedList as $trait ) {
            $traitsList[] = $this->SelectEntityByName( trim( $trait ) );
        }

        return $this->EntityFilter( $traitsList );
    }

    private function EntityFilter(array $entities) : array {
        return array_filter( $entities, function($e) {
            return !is_null( $e );
        } );
    }

    private function SelectEntityByName(string $n) : ?FileType {

        $entity = array_filter( $this->depList, function(FileType $f) use ($n) {
            return $f->fileName == $n . ".php";
        });
        if ( class_exists( $n ) || interface_exists( $n ) ) return null;
        if ( count( $entity ) == 0 ) throw new \Exception("Fatal error: entity {$n} not found in the scope!");

        return $entity[ array_key_first( $entity ) ];
    }

    private function ParseFileType() : string {
        if ( $this->IsClass() ) return FileEntityType::$class;
        if ( $this->IsAbstractClass() ) return FileEntityType::$abstractClass;
        if ( $this->IsInterface() ) return FileEntityType::$interface;
        if ( $this->IsTrait() ) return FileEntityType::$trait;
        return FileEntityType::$undefined;
    }

    private function IsClass() : bool {
        return stripos( $this->fileContent, "class" ) !== false &&
            !stripos( $this->fileContent, "abstract" );
    }

    private function IsAbstractClass() : bool {
        return stripos( $this->fileContent, "class" ) !== false;
    }

    private function IsInterface() : bool {
        return stripos( $this->fileContent, "interface" ) !== false;
    }

    private function IsTrait() : bool {
        return stripos( $this->fileContent, "trait" ) !== false;
    }

    private function ParseFile(string $f) : void {
        $this->fileContent = file_get_contents( $f );
    }

}