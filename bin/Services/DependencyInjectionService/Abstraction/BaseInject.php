<?php

namespace bin\Services\DependencyInjectionService\Abstraction;

use bin\Services\DependencyInjectionService\Entities\InjectEntity;
use bin\Services\DependencyInjectionService\Traits\SystemReflection;

abstract class BaseInject extends ArrayLinq {
    use SystemReflection;
    /**
     * @var InjectEntity[]
     */
    protected array $instances = [];

    /**
     * @var ?self
     */
    protected static ?self $instance = null;

    /**
     * @param string $binder
     * @return void
     */
    public abstract function Bind( string $binder ) : void;

    /**
     * @param string $bindable
     * @return void
     */
    public abstract function To( string $bindable ) : void;

    /**
     * @return void
     */
    public abstract function InTransientScope() : void;

    /**
     * @return void
     */
    public abstract function InRequestScope() : void;

    /**
     * @param string $class
     * @param string|null $method = null
     * @param array $args = []
     */
    public abstract function Inject(string $class, string $method = null, array $args = []);

    /**
     * @return self
     */
    public static function GetInjectExecutor() : BaseInject {
        if (self::$instance == null) {
            $classList = array_filter(get_declared_classes(), function($a) {
                return strpos($a, "Services\DependencyInjectionService\Implementation") !== false;
            });
            foreach ($classList as $class) {
                if (class_exists($class) && is_subclass_of($class, "bin\Services\DependencyInjectionService\Abstraction\BaseInject")) {
                    self::$instance = new $class();
                }
            }
            if (self::$instance == null) {
                echo "Fatal: no inject module detected";
                die;
            }
        }
        return self::$instance;
    }
}