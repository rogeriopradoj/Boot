<?php

namespace Jam\Test\Runner;

use Jam\Runner\Bootstrap;

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
    
}

