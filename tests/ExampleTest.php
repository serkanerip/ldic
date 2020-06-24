<?php

namespace League\Skeleton\Test;

use Erip\LPHPDic\LDic;
use Erip\LPHPDic\Test\stubs\ProductsUtil;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{

    public function diContainerBuilder() : LDic
    {
        return new LDic();
    }

    public function testShouldReturnObject_WhenTryingToGetRegisteredInstance()
    {
        // arrange
        $container = $this->diContainerBuilder();
        $container->register(new ProductsUtil());

        // act
        $productsUtil = $container->resolve(ProductsUtil::class);

        // assert
        $this->assertSame(ProductsUtil::class, get_class($productsUtil));
    }

    public function testShouldReturnObject_WhenTryingToGetLazyRegisteredInstance()
    {
        // arrange
        $container = $this->diContainerBuilder();
        $container->lazyRegister(function(): ProductsUtil {
            return new ProductsUtil();
        });

        // act
        $productsUtil = $container->resolve(ProductsUtil::class);

        // assert
        $this->assertSame(ProductsUtil::class, get_class($productsUtil));
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsException_WhenTryingToGetNotRegisteredInstance()
    {
        // arrange
        $container = $this->diContainerBuilder();

        // act
        $productsUtil = $container->resolve(ProductsUtil::class);
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsException_WhenTryingToRegisterNull()
    {
        // arrange
        $container = $this->diContainerBuilder();

        // act
        $container->register(null);
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsException_WhenLazyRegister_GivenFunctionThatDontReturnAnObject()
    {
        // arrange
        $container = $this->diContainerBuilder();

        // act
        $container->lazyRegister(function(){});
    }

    /**
     * @expectedException TypeError
     */
    public function testThrowsException_WhenLazyRegister_GivenNull()
    {
        // arrange
        $container = $this->diContainerBuilder();

        // act
        $container->lazyRegister(null);
    }
}
