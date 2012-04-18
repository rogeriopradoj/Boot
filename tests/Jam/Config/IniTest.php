<?php

namespace Jam\Config;

class IniTest extends \PHPUnit_Framework_TestCase
{

    private $_string = <<<'INI'
[foo]
a = 1

b.first     = "One"
b.second    = "Two"
b.third     = "Three"

c.d.e.f     = (PHP_EOL)

[bar : foo]
a = 2
b.first     = "Uno"


[baz : bar]
b.third     = "Tres"

INI;
    private $_file;

    protected function setUp()
    {
        $this->_file = sys_get_temp_dir()  
                     . DIRECTORY_SEPARATOR
                     . uniqid() . '.ini';
        file_put_contents($this->_file, $this->_string);
    }

    public function testClassCanReadString()
    {
        $ini = new Ini($this->_string, 'bar');
        $this->assertInternalType('array', $ini->toArray());
    }

    public function testClassCanReadFile()
    {
        $ini = new Ini($this->_file, 'baz');
        $this->assertInternalType('array', $ini->toArray());
    }
    
    protected function tearDown()
    {
        unlink($this->_file);
    }


}

