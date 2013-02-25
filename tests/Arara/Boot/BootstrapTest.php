<?php

namespace Arara\Boot;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Arara\Boot\Bootstrap::__construct
     */
    public function testMustHaveConfigAndEnvironmentOnConstructor()
    {
        $config = array('foo' => 'bar');
        $environment = 'live';
        $bootstrap = new Bootstrap($config, $environment);

        $this->assertAttributeEquals(new Config($config), 'config', $bootstrap);
        $this->assertAttributeSame($environment, 'environment', $bootstrap);
    }

    /**
     * @covers Arara\Boot\Bootstrap::getConfig
     */
    public function testShouldGetTheDefinedConfig()
    {
        $config = array('foo' => 'bar');
        $bootstrap = new Bootstrap($config, 'live');

        $this->assertEquals(new Config($config), $bootstrap->getConfig());
    }

    /**
     * @covers Arara\Boot\Bootstrap::getEnvironment
     */
    public function testShouldGetTheDefinedEnvironment()
    {
        $environment = 'chimichanga';
        $bootstrap = new Bootstrap(array(), $environment);

        $this->assertSame($environment, $bootstrap->getEnvironment());
    }

    /**
     * @covers Arara\Boot\Bootstrap::getProviderLoader
     */
    public function testShouldGetAProviderLoader()
    {
        $bootstrap = new Bootstrap(array(), 'live');

        $this->assertInstanceOf('Arara\Boot\Provider\Loader', $bootstrap->getProviderLoader());
    }

    /**
     * @covers Arara\Boot\Bootstrap::run
     */
    public function testShouldRunWithAValidCallback()
    {
        $bootstrap = $this->getMock('Arara\Boot\Bootstrap', array('getConfig'), array(), '', false);
        $bootstrap->expects($this->once())
                  ->method('getConfig');
        $callback = function ($bootstrap) {
            $bootstrap->getConfig();// Assert
        };
        $bootstrap->run($callback);
    }

    /**
     * @covers Arara\Boot\Bootstrap::run
     */
    public function testShouldRunWithoutAnyCallbackAndLoadProviders()
    {
        $bootstrap = $this->getMock('Arara\Boot\Bootstrap', array('getProviderLoader'), array(), '', false);

        $loader = $this->getMock('Arara\Boot\Provider\Loader', array('load'));
        $loader->expects($this->once())
               ->method('load')
               ->with($bootstrap);

        $bootstrap->expects($this->once())
                  ->method('getProviderLoader')
                  ->will($this->returnValue($loader));

        $bootstrap->run();
    }

    /**
     * @covers Arara\Boot\Bootstrap::run
     * @expectedException \InvalidArgumentException
     */
    public function testMustThrowsAnExceptionWhenCallbackIsNotValid()
    {
        $bootstrap = new Bootstrap(array(), 'live');
        $bootstrap->run(array(1, 2, 3, 4, 5));
    }

    /**
     * @covers Arara\Boot\Bootstrap::getProvider
     */
    public function testShouldReturnAProviderByName()
    {
        $name = 'myProvider';
        $provider = $this->getMock('Arara\Boot\Provider\Provider', array('__construct',  '__invoke'));

        $bootstrap = new Bootstrap(array(), 'live');

        $loader = $this->getMock('\Arara\Boot\Provider\Loader', array('get'));
        $loader->expects($this->any())
               ->method('get')
               ->with($name)
               ->will($this->returnValue($provider));

        $property = new \ReflectionProperty($bootstrap, 'providerLoader');
        $property->setAccessible(true);
        $property->setValue($bootstrap, $loader);

        $this->assertSame($provider, $bootstrap->getProvider($name));
    }

}
