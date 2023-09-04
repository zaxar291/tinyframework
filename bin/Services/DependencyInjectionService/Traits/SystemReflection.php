<?php

namespace bin\Services\DependencyInjectionService\Traits;

use bin\Services\DependencyInjectionService\Exceptions\ReflectException;

trait SystemReflection {

    /**
     * @param string $c
     * @param string $m
     * @return \ReflectionMethod
     * @throws \Exception
     */
    public function GetType(string $c, string $m) : \ReflectionMethod {
        if (!method_exists( $c, $m )) {
            throw new \Exception("System.Reflection: fatal, cannot find {$m} in {$c}");
        }
        return new \ReflectionMethod($c, $m);
    }

    /**
     * @param string $c
     * @param string $m
     * @return \ReflectionParameter[]
     */
    public function GetParameters(string $c, string $m) : ?array {
        return $this->GetType( $c, $m )->getParameters();
    }

    /**
     * @param string $className
     * @return \ReflectionClass
     * @throws ReflectException
     */
    public function GetClass(string $className) : \ReflectionClass {
        if ( !class_exists( $className ) ) {
            throw new ReflectException("class {$className} not found in the current scope");
        }
        return new \ReflectionClass($className);
    }

    /**
     * @param string $class
     * @param string $method
     * @return \ReflectionMethod
     * @throws \ReflectionException|ReflectException
     */
    public function GetMethod(string $class, string $method) : \ReflectionMethod {
        if ( !class_exists( $class ) ) {
            throw new ReflectException("class {$class} not found in the current scope");
        }
        return new \ReflectionMethod($class, $method);
    }

    /**
     * @param string $className
     * @param array $args
     * @return object
     * @throws ReflectException|\ReflectionException
     */
    public function CreateInstance(string $className, array $args = []) : object {
        if ( ! class_exists( $className ) ) {
            throw new ReflectException("class {$className} not found in the current scope");
        }
        $c = new \ReflectionClass( $className );
        if ( count( $args ) == 0 ) {
            return $c->newInstance();
        }
        return $c->newInstanceArgs( $args );
    }

    /**
     * @param string $c;
     * @param string $m;
     * @return string;
     * @throws \ReflectionException
     */
    public function GetComments(string $c, string $m) : string {
        $m = new \ReflectionMethod($c, $m);
        return $m->getDocComment();
    }

    /**
     * @param string $c;
     * @param string $m;
     * @return string[];
     * @throws \ReflectionException
     */
    public function GetCommentsAsArray(string $c, string $m) : array {
        $c = $this->GetComments($c, $m);
        if ( trim( $c ) !== "" ) {
            return array_filter(explode("*", str_ireplace(["/**", "*/"], "", $c)), fn(string $c) => trim($c) !== "");
        }
        return [];
    }

    /**
     * @param string $class
     * @return string
     */
    public function ApplyNamespace(string $class) : string {
        $allDeclaredClasses = get_declared_classes();
        if (count($allDeclaredClasses) > 0) {
            foreach ($allDeclaredClasses as $declaredClass) {
                $namespaceParts = explode("\\", $declaredClass);
                $className = $namespaceParts[count( $namespaceParts ) - 1];
                if ( $className == $class ) {
                    return $declaredClass;
                }
            }
        }
        $allDeclaredInterfaces = get_declared_interfaces();
        if (count($allDeclaredInterfaces) > 0) {
            foreach ($allDeclaredInterfaces as $declaredInterface) {
                $namespaceParts = explode("\\", $declaredInterface);
                $interfaceName = $namespaceParts[count( $namespaceParts ) - 1];
                if ($interfaceName == $class) {
                    return $declaredInterface;
                }
            }
        }
        return $class;
    }

    public function ArrayToClass(array $array, $class) {
        if ( count( $array ) > 0 ) {
            foreach ($array as $key => $value) {
                if ( isset( $class->{$key} ) ) {
                    $class->{$key} = $value;
                }
            }
        }
        return $class;
    }
}