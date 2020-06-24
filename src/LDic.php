<?php
namespace Erip\LPHPDic;

use Exception;
use ReflectionFunction;

/**
 * Interface ILDic
 * @package Erip\LPHPDic
 */
interface ILDic
{

    /**
     * @param $instance
     */
    public function register($instance): void;

    /**
     * @param string $className
     * @return mixed
     */
    public function resolve(string $className);


    /**
     * @param callable $func
     * @param bool $isSingleton
     */
    public function lazyRegister(callable $func, bool $isSingleton = true): void;
}

/**
 * Class LDic
 * @package Erip\LPHPDic
 */
class LDic implements ILDic
{

    /**
     * @var array
     */
    private $deps = array();
    /**
     * @var array
     */
    private $lazyDeps = array();


    /**
     * @param $instance
     * @throws Exception
     */
    public function register($instance): void
    {
        if ($instance === null || is_object($instance) === false) {
            throw new Exception('Dependency should be a object!');
        }

        $className = get_class($instance);
        $this->deps[$className] = $instance;
    }

    /**
     * @param callable $func
     * @param bool $isSingleton
     * @throws \ReflectionException
     */
    public function lazyRegister(callable $func, bool $isSingleton = true): void
    {
        $functionReflection = new ReflectionFunction($func);

        if ($functionReflection->getReturnType() === null) {
            throw new Exception('Object creater function must return a object.');
        }

        $className = $functionReflection->getReturnType()->getName();
        $this->lazyDeps[$className] = array(
            "isSingleton" => $isSingleton,
            "creator" => $func
        );
    }

    /**
     * @param string $className
     * @return bool
     */
    private function checkDepExistsInLazyRegisters(string $className): bool
    {
        return array_key_exists($className, $this->lazyDeps);
    }

    /**
     * @param string $className
     * @return mixed
     * @throws Exception
     */
    private function executeLazyObjectCreatorFunction(string $className)
    {
        $creatorFunction = $this->lazyDeps[$className]['creator'];
        $isSingleton = $this->lazyDeps[$className]['isSingleton'];

        $instance = $creatorFunction();
        if ($isSingleton) {
            $this->register($instance);
            unset($this->lazyDeps[$className]);
        }
        return $instance;
    }

    /**
     * @param string $className
     * @return mixed
     * @throws Exception
     */
    public function resolve(string $className)
    {
        if (array_key_exists($className, $this->deps) === false) {
            if ($this->checkDepExistsInLazyRegisters($className)) {
                return $this->executeLazyObjectCreatorFunction($className);
            }
            throw new Exception('Dependency not exists in container.');
        }

        return $this->deps[$className];
    }
}
