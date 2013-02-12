<?php

namespace Jam\Bootstrapper;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Jam\Bootstrapper\Bootstrap::__construct
     */
    public function testMustHaveConfigAndEnvironmentOnConstructor()
    {
        $config = array('foo' => 'bar');
        $environment = 'live';
        $bootstrap = new Bootstrap($config, $environment);

        $this->assertAttributeSame($config, 'config', $bootstrap);
        $this->assertAttributeSame($environment, 'environment', $bootstrap);
    }

    /**
     * @covers Jam\Bootstrapper\Bootstrap::getConfig
     */
    public function testShouldGetTheDefinedConfig()
    {
        $config = array('foo' => 'bar');
        $bootstrap = new Bootstrap($config, 'live');

        $this->assertSame($config, $bootstrap->getConfig());
    }

    /**
     * @covers Jam\Bootstrapper\Bootstrap::getEnvironment
     */
    public function testShouldGetTheDefinedEnvironment()
    {
        $environment = 'chimichanga';
        $bootstrap = new Bootstrap(array(), $environment);

        $this->assertSame($environment, $bootstrap->getEnvironment());
    }

    /**
     * @covers Jam\Bootstrapper\Bootstrap::getProviderLoader
     */
    public function testShouldGetAProviderLoader()
    {
        $bootstrap = new Bootstrap(array(), 'live');

        $this->assertInstanceOf('Jam\Bootstrapper\Provider\Loader', $bootstrap->getProviderLoader());
    }

    /**
     * @covers Jam\Bootstrapper\Bootstrap::run
     */
    public function testShouldRunWithAValidCallback()
    {
        $bootstrap = $this->getMock('Jam\Bootstrapper\Bootstrap', array('getConfig'), array(), '', false);
        $bootstrap->expects($this->once())
                  ->method('getConfig');
        $callback = function ($bootstrap) {
            $bootstrap->getConfig();// Assert
        };
        $bootstrap->run($callback);
    }

    /**
     * @covers Jam\Bootstrapper\Bootstrap::run
     */
    public function testShouldRunWithoutAnyCallbackAndLoadProviders()
    {
        $bootstrap = $this->getMock('Jam\Bootstrapper\Bootstrap', array('getProviderLoader'), array(), '', false);

        $loader = $this->getMock('Jam\Bootstrapper\Provider\Loader', array('load'));
        $loader->expects($this->once())
               ->method('load')
               ->with($bootstrap);

        $bootstrap->expects($this->once())
                  ->method('getProviderLoader')
                  ->will($this->returnValue($loader));

        $bootstrap->run();
    }

    /**
     * @covers Jam\Bootstrapper\Bootstrap::run
     * @expectedException \InvalidArgumentException
     */
    public function testMustThrowsAnExceptionWhenCallbackIsNotValid()
    {
        $bootstrap = new Bootstrap(array(), 'live');
        $bootstrap->run(array(1, 2, 3, 4, 5));
    }

}
