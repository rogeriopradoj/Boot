<?php

namespace Arara\Boot\Provider;

use Arara\Boot\Bootstrap,
    Arara\Boot\Config;

class LoaderTest extends \PHPUnit_Framework_TestCase
{

    private function provider($bootstrap)
    {
        $provider = $this->getMock(
            'Arara\Boot\Provider\Provider',
            array('__construct', '__invoke'),
            array(array(), $bootstrap),
            '',
            false
        );

        return $provider;
    }

    /**
     * @covers Arara\Boot\Provider\Loader::__construct
     */
    public function testShouldAddADefaultNamespaceOnConstructor()
    {
        $loader = new Loader();

        $this->assertAttributeSame(array('Arara\Boot\Provider\\'), 'namespaces', $loader);
    }

    /**
     * @covers Arara\Boot\Provider\Loader::addNamespace
     * @covers Arara\Boot\Provider\Loader::getNamespaces
     * @depends testShouldAddADefaultNamespaceOnConstructor
     */
    public function testShouldAddAndRetrieveNamespacesInReverseOrder()
    {
        $loader = new Loader();
        $loader->addNamespace('Foo\Bar');

        $this->assertSame(
            array(
                'Foo\Bar',
                'Arara\Boot\Provider\\',
            ),
            $loader->getNamespaces()
        );
    }

    /**
     * @covers Arara\Boot\Provider\Loader::has
     */
    public function testShouldCheckIfLoaderHasAProvider()
    {
        $bootstrap = new Bootstrap(array(), 'test');
        $provider = $this->provider($bootstrap);
        $providerName = 'providerOne';
        $loader = new Loader();

        $reflection = new \ReflectionProperty($loader, 'providers');
        $reflection->setAccessible(true);
        $reflection->setValue($loader, array($providerName => $provider));

        $this->assertTrue($loader->has($providerName));
        $this->assertFalse($loader->has('Ma oe!'));
    }

    /**
     * @covers Arara\Boot\Provider\Loader::get
     * @depends testShouldCheckIfLoaderHasAProvider
     */
    public function testShouldGetAValidProvider()
    {
        $bootstrap = new Bootstrap(array(), 'test');
        $provider = $this->provider($bootstrap);
        $provider->expects($this->once())
                 ->method('__invoke')
                 ->will($this->returnValue($provider));
        $providerName = 'providerOne';
        $loader = new Loader();

        $reflection = new \ReflectionProperty($loader, 'providers');
        $reflection->setAccessible(true);
        $reflection->setValue($loader, array($providerName => $provider));

        $this->assertSame($provider, $loader->get($providerName));
    }

    /**
     * @covers Arara\Boot\Provider\Loader::get
     * @depends testShouldCheckIfLoaderHasAProvider
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage There is no defined provider called by "Go! Go! Go!"
     */
    public function testShouldThrowsAnExceptionWhenGettingAnInvalidProvider()
    {
        $loader = new Loader();
        $loader->get('Go! Go! Go!');
    }

    /**
     * @covers Arara\Boot\Provider\Loader::load
     */
    public function testShouldNotTryToLoadKeysThatAreNotProviders()
    {
        $config = array(
            'KeyThatAreNotAProvider' => array(),
            'AnotherKeyThatAreNotAProvider' => array(),
        );
        $bootstrap = new Bootstrap($config, 'whatever');

        $loader = new Loader();
        $loader->load($bootstrap);

        $this->assertFalse($loader->has('KeyThatAreNotAProvider'));
        $this->assertFalse($loader->has('AnotherKeyThatAreNotAProvider'));
    }

    /**
     * @covers Arara\Boot\Provider\Loader::load
     */
    public function testShouldLoadKeysThatAreValidProviders()
    {
        $config = array(
            'testProviderOne' => array(),
            'testProviderTwo' => array(),
        );
        $bootstrap = new Bootstrap($config, 'whatever');

        $loader = new Loader();
        $loader->load($bootstrap);

        $this->assertInstanceOf('Arara\Boot\Provider\TestProviderOne', $loader->get('testProviderOne'));
        $this->assertInstanceOf('Arara\Boot\Provider\TestProviderTwo', $loader->get('testProviderTwo'));
    }

    /**
     * @covers Arara\Boot\Provider\Loader::load
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage "Arara\Boot\Provider\TestNotProvider" is not a valid provider class
     */
    public function testShouldThrowsAnExceptionWithInvalidProviders()
    {
        $config = array(
            'testNotProvider' => array(),
        );
        $bootstrap = new Bootstrap($config, 'whatever');

        $loader = new Loader();
        $loader->load($bootstrap);
    }

}

class TestProviderOne implements Provider
{

    public function __construct(Config $config, \Arara\Boot\Bootstrap $bootstrap)
    {

    }

    public function __invoke()
    {
        return $this;
    }

}

class TestProviderTwo implements Provider
{

    public function __construct(Config $config, Bootstrap $bootstrap)
    {

    }

    public function __invoke()
    {
        return $this;
    }

}

class TestNotProvider
{

}
