<?php

namespace Jam\Test\Runner;

use Jam\Runner\Bootstrap,
    Jam\Runner\Provider\Provider;


class BootstrapTest extends \PHPUnit_Framework_TestCase
{

    public function test_default_properties_of_bootstrap()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_1.ini', 'production');
        $this->assertEquals('production', $boostrap->getEnvironment());
        $this->assertInstanceOf('Jam\\Config\\Ini', $boostrap->getConfig());
        $this->assertEquals(array(), $boostrap->getProviders());
        $this->assertEquals(array('Jam\\Runner\\Provider\\'), $boostrap->getProviderNamespaces());
    }

    public function test_providers_namespace()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_1.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__);

        $this->assertEquals(
            array(
                __NAMESPACE__,
                'Jam\\Runner\\Provider\\',
            ),
            $boostrap->getProviderNamespaces()
        );
    }

    public function test_load_providers()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');
        $boostrap->loadProviders();
        $this->assertEquals(
            array(
                'ProviderOne',
                'ProviderTwo',
            ),
            array_keys($boostrap->getProviders())
        );
    }

    public function test_load_invalid_providers_shoul_throws_exception()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_3.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');

        $this->setExpectedException('UnexpectedValueException');
        $boostrap->loadProviders();
    }

    public function test_has_provider_should_return_true_with_a_valid_provider()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');
        $boostrap->loadProviders();
        $this->assertTrue($boostrap->hasProvider('ProviderOne'));
    }

    public function test_has_provider_should_return_false_with_an_ivalid_provider()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');
        $boostrap->loadProviders();
        $this->assertFalse($boostrap->hasProvider('ProviderThree'));
    }

    public function test_get_provider_should_return_a_valid_provider()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');
        $boostrap->loadProviders();
        $this->assertInstanceOf('Jam\\Runner\\Provider\\Provider', $boostrap->getProvider('ProviderOne'));
    }

    public function test_get_provider_should_return_null_for_an_ivalid_provider()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');
        $boostrap->loadProviders();
        $this->assertNull($boostrap->getProvider('ProviderThree'));
    }

    public function test_run_without_a_callback_should_load_providers()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');
        $return     = $boostrap->run();

        $this->assertEquals(
            array(
                'ProviderOne',
                'ProviderTwo',
            ),
            array_keys($boostrap->getProviders())
        );
        $this->assertSame($return, $boostrap);
    }

    public function test_run_with_a_valid_callback()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');

        $callback   = function () {
            return 'Jam/Runner';
        };
        $return     = $boostrap->run($callback);

        $this->assertEquals(
            array(),
            $boostrap->getProviders()
        );
        $this->assertEquals('Jam/Runner', $return);
    }

    public function test_run_with_an_invalid_callback()
    {
        $boostrap = new Bootstrap(__DIR__ . '/_files/example_2.ini', 'production');
        $boostrap->addProviderNamespace(__NAMESPACE__ . '\\');

        $callback   = 'Jam/Runner';
        
        $this->setExpectedException('InvalidArgumentException');
        $boostrap->run($callback);
    }


}


class ProviderOne implements Provider
{

    private $config;

    public function get()
    {
        return $this;
    }

    public function init(\Jam\Config\Arr $config)
    {
        $this->config = $config;
        return $this;
    }


}

class ProviderTwo implements Provider
{

    private $config;

    public function get()
    {
        return $this;
    }

    public function init(\Jam\Config\Arr $config)
    {
        $this->config = $config;
        return $this;
    }


}