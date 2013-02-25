<?php

namespace Arara\Boot;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Arara\Boot\Config::__construct
     */
    public function testShouldDefinedDataOnConstructor()
    {
        $data = array('foo' => 1, 'bar' => 2, 'baz' => array(3, 4));
        $config = new Config($data);

        $this->assertAttributeSame($data, 'data', $config);
    }

    /**
     * @covers Arara\Boot\Config::has
     */
    public function testShouldCheckIfAKeyExists()
    {
        $data = array('foo' => 'bar');
        $config = new Config($data);

        $this->assertTrue($config->has('foo'));
    }

    /**
     * @covers Arara\Boot\Config::has
     */
    public function testShouldCheckIfAKeyNotExists()
    {
        $data = array('foo' => 'bar', 'baz' => array(3, 4));
        $config = new Config($data);

        $this->assertFalse($config->has('bar'));
    }

    /**
     * @covers Arara\Boot\Config::get
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage Property "foo-invalid" was not foun
     */
    public function testShouldThrowsAnExceptionWhenGettingKeyIfDoNotExists()
    {
        $key = 'foo';
        $data = array($key => 'bar');
        $config = new Config($data);

        $config->get($key . '-invalid');
    }

    /**
     * @covers Arara\Boot\Config::get
     */
    public function testShouldGetSimpleValues()
    {
        $key = 'foo';
        $data = array($key => 'bar');
        $config = new Config($data);

        $this->assertSame($data[$key], $config->get($key));
    }

    /**
     * @covers Arara\Boot\Config::get
     */
    public function testShouldArrayValuesAsObject()
    {
        $key = 'foo';
        $data = array($key => array('bar' => range(1, 10), 'baz' => range(100, 90)));
        $config = new Config($data);

        $this->assertInstanceOf('Arara\Boot\Config', $config->get($key));
    }

    /**
     * @covers Arara\Boot\Config::count
     */
    public function testShouldCountCorrectly()
    {
        $config = new Config(range(1, 900));

        $this->assertSame(900, $config->count());
    }

    /**
     * @covers Arara\Boot\Config::key
     */
    public function testShoultReturnTheCurrentKey()
    {
        $config = new Config(array('a' => 1, 'b' => 2, 'c' => 3));

        $this->assertSame('a', $config->key());
    }

    /**
     * @covers Arara\Boot\Config::key
     */
    public function testShoultReturnNullIfThereIsNoCurrentKey()
    {
        $config = new Config(array());

        $this->assertNull($config->key());
    }

    /**
     * @covers Arara\Boot\Config::current
     * @depends testShoultReturnTheCurrentKey
     */
    public function testShouldGetCurrentElement()
    {
        $config = new Config(array('a' => 1, 'b' => 2, 'c' => 3));

        $this->assertSame(1, $config->current());
    }

    /**
     * @covers Arara\Boot\Config::current
     * @depends testShoultReturnNullIfThereIsNoCurrentKey
     */
    public function testShouldReturnNullIfThereIsNoCurrentElement()
    {
        $config = new Config(array());

        $this->assertNull($config->current());
    }

    /**
     * @covers Arara\Boot\Config::next
     */
    public function testShouldReturnFalseIfThereIsNoNextElement()
    {
        $config = new Config(array());

        $this->assertFalse($config->next());
    }

    /**
     * @covers Arara\Boot\Config::next
     */
    public function testShouldReturnTheNextElement()
    {
        $config = new Config(array('a' => 1, 'b' => 2, 'c' => 3));

        $this->assertSame(2, $config->next());
        $this->assertSame(2, $config->current());
        $config->next();
        $this->assertSame(3, $config->current());
    }

    /**
     * @covers Arara\Boot\Config::rewind
     * @depends testShouldReturnTheNextElement
     */
    public function testShouldRewindInternalArray()
    {
        $config = new Config(array('a' => 1, 'b' => 2, 'c' => 3));

        $config->next();
        $config->next();
        $config->rewind();

        $this->assertSame(1, $config->current());
    }

    /**
     * @covers Arara\Boot\Config::valid
     * @depends testShoultReturnTheCurrentKey
     */
    public function testShouldReturnTrueIfIsValid()
    {
        $config = new Config(array('a' => 1));

        $this->assertTrue($config->valid());
    }

    /**
     * @covers Arara\Boot\Config::valid
     * @depends testShoultReturnNullIfThereIsNoCurrentKey
     */
    public function testShouldReturnFalseIfIsValid()
    {
        $config = new Config(array('a' => 1));
        $config->next();

        $this->assertFalse($config->valid());
    }

}
