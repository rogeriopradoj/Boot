<?php

namespace Jam\Bootstrapper\Provider;

use Jam\Bootstrapper\Bootstrap;

function spl_autoload_register($callback)
{
    $GLOBALS['spl_autoload_register'] = $callback;
}

class AutoloaderPrefixTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $GLOBALS['spl_autoload_register'] = null;
    }

    /**
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::__construct
     */
    public function testShouldRegisterAutoloaderOnConstructor()
    {
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new AutoloaderPrefix(array(), $boostrap);

        $this->assertSame(array($provider, 'loadClass'), $GLOBALS['spl_autoload_register']);
    }

    /**
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::addPrefix
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::getPrefixes
     */
    public function testShouldAddAndRetrievePrefixes()
    {
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new AutoloaderPrefix(array(), $boostrap);
        $provider->addPrefix(__NAMESPACE__, __DIR__);

        $this->assertSame(array(__NAMESPACE__ => __DIR__), $provider->getPrefixes());

    }

    /**
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::__construct
     * @depends testShouldAddAndRetrievePrefixes
     */
    public function testShouldAddPrefixesOnConstructor()
    {
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new AutoloaderPrefix(array(__NAMESPACE__ => __DIR__), $boostrap);

        $this->assertSame(array(__NAMESPACE__ => __DIR__), $provider->getPrefixes());
    }

    /**
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::__invoke
     */
    public function testShouldReturnTheSelfWhenInvoked()
    {
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new AutoloaderPrefix(array(), $boostrap);

        $this->assertSame($provider, $provider());
    }

    /**
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::loadClass
     */
    public function testShouldNotTryToLoadIfIsNotOnTheSameNamespace()
    {
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new AutoloaderPrefix(
            array(
                __NAMESPACE__ => __DIR__
            ),
            $boostrap
        );
        $this->assertFalse($provider->loadClass('Service\\Foo'));
    }

    /**
     * @covers Jam\Bootstrapper\Provider\AutoloaderPrefix::loadClass
     */
    public function testShouldLoadClass()
    {
        $className = 'Model\\Foo\\Bar';
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new AutoloaderPrefix(
            array(
                'Model' => realpath(__DIR__ . '/../../../fixtures/models')
            ),
            $boostrap
        );
        $this->assertTrue($provider->loadClass($className));
    }

}
