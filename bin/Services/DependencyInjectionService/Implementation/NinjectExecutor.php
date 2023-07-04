<?php

namespace bin\Services\DependencyInjectionService\Implementation;

use bin\Services\DependencyInjectionService\Abstraction\BaseInject;
use bin\Services\DependencyInjectionService\Entities\InjectEntity;
use bin\Services\DependencyInjectionService\Entities\InjectExtraParam;

class NinjectExecutor extends BaseInject {

    private string $constructorMethod = "";
    private array $extraArguments;

    public function __construct() {
        $this->constructorMethod = "__construct";
        $this->extraArguments = [];
    }

    public function Bind(string $binder) : void {
        $selection = array_filter( $this->instances, function (InjectEntity $instance) use ($binder) {
            return $instance->binder == $binder;
        });
        if ($selection == null) {
            $this->instances[] = new InjectEntity(
                $this->ApplyNamespace($binder)
            );
        }
    }

    public function To(string $bindable) : void {
        $selection = $this->Last( $this->instances );
        if ( !is_object( $selection ) ) {
            throw new \Exception("Fatal: no objects detected, you shouldn't call BaseInject directly, please, use InjectBinder to init injection");
        }
        $selection->bindable = $this->ApplyNamespace($bindable);

    }

    public function InTransientScope() : void {
        $selection = $this->Last( $this->instances );
        if ( !is_object( $selection ) ) {
            throw new \Exception("Fatal: no objects detected, you shouldn't call InjectScopeBuilder directly, please, use InjectBindable to init injection");
        }
        $selection->scope = 2;
    }

    public function InRequestScope() : void {
        $selection = $this->Last( $this->instances );
        if ( !is_object( $selection ) ) {
            throw new \Exception("Fatal: no objects detected, you shouldn't call InjectScopeBuilder directly, please, use InjectBindable to init injection");
        }
        $selection->scope = 1;
    }

    public function Inject(string $class, string $method = null, array $args = []) {
        $class = $this->ApplyNamespace($class);
        $this->extraArguments = $args;
        if ( !is_null( $method ) ) {
            return $this->CreateInstanceAndCall($class, $method);
        }
        return
            method_exists( $class, $this->constructorMethod ) && $this->CheckInjectionPossibility( $class, $this->constructorMethod )
                ? $this->CreateExecutionInstance( $class, $this->constructorMethod )
                : $this->CreateExecutionInstance( $class );
    }

    protected function CreateInstanceAndCall(string $class, string $method) {
        $instance = $this->CreateExecutionInstance( $class, $this->constructorMethod );
        $this->CheckInjectionPossibility( $class, $method );
        $paramsList = $this->PrepareParamsListForExecution( $class, $method );
        $pList = [];
        foreach ($paramsList as $param) {
            if ( $param->isCustomParam ) {
                $pList[] = $param->bindable;
            } else {
                $pList[] = $param->instance;
            }
        }
        $methodDescriptor = $this->GetMethod($class, $method);
        return $methodDescriptor->invokeArgs($instance, $pList);
    }

    protected function CheckInjectionPossibility(string $c, string $m) : bool {
        if ( method_exists( $c, $m ) ) {
            $methodParams = $this->GetParameters( $c, $m );
            if ( is_array( $methodParams ) && count( $methodParams ) ) {
                foreach ($methodParams as $param) {
                    $type = $param->getType();
                    $name = $param->getName();
                    $canBeNull = $param->allowsNull();
                    if ( !$this->IsInjectionExists( $type, $name ) && !$canBeNull && !$this->IsCustomParam($type) ) {
                        throw new \Exception("Ninject.Fatal: method {$m} in class {$c} requires parameter of type {$type}, which wasn't bind to the scope.");
                    }
                }
            }
        }
        return true;
    }

    protected function IsInjectionExists(string $type, string $name) : bool {
        $instance = $this->Select( $this->instances, function(InjectEntity $i) use ($type) {
            return $i->binder == $type || strpos( $type, $i->binder );
        });
        if ( is_null( $instance ) ) {
            $customParam = $this->Select( $this->extraArguments, function(InjectExtraParam $param) use ($type, $name) {
                return $param->name == $name && $param->type == $type;
            } );
            return !is_null( $customParam );
        }
        return true;
    }
    protected function IsCustomParam(string $type) : bool {
        return in_array( $type, ["string", "?string", "int"] );
    }

    protected function CreateExecutionInstance(string $c, string $m = "") : object {
        $c = $this->ApplyNamespace($c);
        $paramsList = [];
        if ( $m !== "" ) {
            $paramsList = $this->PrepareParamsListForExecution( $c, $m );
        }
        if ( count( $paramsList ) == 0 ) {
            return $this->CreateInstance( $c );
        }
        $pList = [];
        foreach ($paramsList as $param) {
            $pList[] = $param->instance;
        }

        return $this->CreateInstance( $c, $pList );
    }

    protected function PrepareParamsListForExecution(string $c, string $m) : array {
        $executionParams = $this->SelectExecutionParams($c, $m);
        $pList = [];
        if (count( $executionParams ) > 0) {
            foreach ($executionParams as $executionParam) {
                if ( !$executionParam->isCustomParam ) {
                    $pList[] = $this->CreateParamInstance($executionParam);
                } else {
                    $pList[] = $executionParam;
                }
            }
        }
        return $pList;
    }

    protected function SelectExecutionParams(string $c, string $m, bool $s = false) : array {
        $executionParams = [];
        if (method_exists( $c, $m )) {
            $classDescription = $this->GetType($c, $m);
            $params = $classDescription->getParameters();
            foreach ($params as $param) {
                $instanceType = $param->getType();
                if ($instanceType !== null && $instanceType != "") {
                    $executionParam = $this->Select($this->instances, function(InjectEntity $i) use ($instanceType, $c) {
                        return $instanceType == $i->binder || strpos( $instanceType, $i->binder );
                    });
                    if ( is_null( $executionParam ) ) {
                        $this->SelectValueByName($param);
                        $customExecutionParam = new InjectEntity(
                            $instanceType,
                            true
                        );
                        $customExecutionParam->bindable = $this->SelectValueByName($param);
                        $customExecutionParam->scope = 1;
                        $executionParams[] = $customExecutionParam;
                    } else {
                        $executionParams[] = $executionParam;
                    }

                }
            }
        }
        return $executionParams;
    }

    public function SelectValueByName(\ReflectionParameter $param) {
        $type = $param->getType();
        $name = $param->getName();
        $allowNull = $param->allowsNull();
        $hasDefaultValue = $param->isOptional();
        $value = $this->Select( $this->extraArguments, function(InjectExtraParam $param) use ($type, $name) {
            return $param->name == $name && $param->type == str_ireplace( ["?"], "", $type );
        } );
        if ( is_null( $value ) && !$allowNull && !$hasDefaultValue ) {
            return $this->ApplyDefaultValue($type);
        }
        if ( is_null( $value ) && $allowNull ) {
            return null;
        }
        if ( is_null( $value ) && $hasDefaultValue ) {
            return $param->getDefaultValue();
        }
        return $value->value;
    }

    public function ApplyDefaultValue(string $type) {
        switch ($type) {
            case "string":
                return "";
            case "Array":
                return [];
            case "Integer":
                return 0;
            default:
                return null;
        }
    }

    protected function CreateParamInstance(InjectEntity $instance) : InjectEntity {
        if ($instance->instance == null && $instance->scope == 1) {
            $this->CheckInjectionPossibility($instance->bindable, $this->constructorMethod);
            $paramList = $this->SelectExecutionParams($instance->bindable, $this->constructorMethod, true);
            if ( is_array( $paramList ) && count( $paramList ) > 0 ) {
                $iList = [];
                foreach ($paramList as $param) {
                    $iList[] = $this->CreateParamInstance($param)->instance;
                }
                $instance->instance = $this->CreateInstance( $instance->bindable, $iList );
            } else {
                $instance->instance = $this->CreateInstance( $instance->bindable );
            }
        }
        return $instance;
    }

}