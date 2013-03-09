<?php

namespace Arara\Boot\Provider;


use Arara\Boot\Bootstrap,
    Arara\Boot\Config;

class PdoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Arara\Boot\Provider\Pdo::__construct
     */
    public function testShouldFactoryWithDnsOnly()
    {
        $config = new Config(array('dsn' => 'sqlite::memory:'));
        $bootstrap = new Bootstrap(array(), 'test', __DIR__);
        $pdo = new Pdo($config, $bootstrap);

        $this->assertAttributeInstanceOf('PDO', 'pdo', $pdo);
    }

    /**
     * @covers Arara\Boot\Provider\Pdo::__construct
     */
    public function testShouldFactoryWithDnsAndUsername()
    {
        $config = new Config(array(
            'dsn' => 'sqlite::memory:',
            'usename' => null,
        ));
        $bootstrap = new Bootstrap(array(), 'test', __DIR__);
        $pdo = new Pdo($config, $bootstrap);

        $this->assertAttributeInstanceOf('PDO', 'pdo', $pdo);
    }

    /**
     * @covers Arara\Boot\Provider\Pdo::__construct
     */
    public function testShouldFactoryWithDnsUsernameAndPassword()
    {
        $config = new Config(array(
            'dsn' => 'sqlite::memory:',
            'usename' => null,
            'password' => null,
        ));
        $bootstrap = new Bootstrap(array(), 'test', __DIR__);
        $pdo = new Pdo($config, $bootstrap);

        $this->assertAttributeInstanceOf('PDO', 'pdo', $pdo);
    }

    /**
     * @covers Arara\Boot\Provider\Pdo::__construct
     */
    public function testShouldFactoryWithDnsUsernamePasswordAndDriverOptions()
    {
        $config = new Config(array(
            'dsn' => 'sqlite::memory:',
            'usename' => null,
            'password' => null,
            'options' => array(\PDO::ATTR_PERSISTENT => true),
        ));
        $bootstrap = new Bootstrap(array(), 'test', __DIR__);
        $pdo = new Pdo($config, $bootstrap);

        $this->assertAttributeInstanceOf('PDO', 'pdo', $pdo);
    }

    /**
     * @covers Arara\Boot\Provider\Pdo::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowsAnExceptionIfOptionsAreNotValid()
    {
        $config = new Config(array());
        $bootstrap = new Bootstrap(array(), 'test', __DIR__);
        new Pdo($config, $bootstrap);
    }

    /**
     * @covers Arara\Boot\Provider\Pdo::__invoke
     */
    public function testShouldReturnPdoObjectionWhenObjectIsInvoked()
    {
        $config = new Config(array('dsn' => 'sqlite::memory:'));
        $bootstrap = new Bootstrap(array(), 'test', __DIR__);
        $pdo = new Pdo($config, $bootstrap);

        $this->assertInstanceOf('PDO', $pdo());
    }

}
