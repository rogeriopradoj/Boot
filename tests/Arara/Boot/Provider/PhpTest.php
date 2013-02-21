<?php

namespace Arara\Boot\Provider;

$GLOBALS['ini_set'] = array();

function ini_set($varname, $newvalue)
{
    $GLOBALS['ini_set'][$varname] = $newvalue;
}

use Arara\Boot\Bootstrap;

class PhpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Arara\Boot\Provider\Php::set
     */
    public function testShouldDefineIniSettings()
    {
        $originalConfig   = array(
            'display_erros' => false,
            'date' => array(
                'timezone' => 'America/Sao_Paulo',
                'default_latitude' => '31.7667',
                'default_longitude' => '35.2333',
            ),
        );
        $iniConfig = array(
            'display_erros' => $originalConfig['display_erros'],
            'date.timezone' => $originalConfig['date']['timezone'],
            'date.default_latitude' => $originalConfig['date']['default_latitude'],
            'date.default_longitude' => $originalConfig['date']['default_longitude'],
        );

        $provider = new Php(array(), new Bootstrap(array(), 'test'));
        $provider->set('display_erros', $originalConfig['display_erros']);
        $provider->set('date', $originalConfig['date']);

        $this->assertSame($iniConfig, $GLOBALS['ini_set']);
    }

    /**
     * @covers Arara\Boot\Provider\Php::__construct
     * @depends testShouldDefineIniSettings
     */
    public function testShouldDefineIniSettingOnConstructor()
    {
        $originalConfig   = array(
            'display_erros' => false,
            'date' => array(
                'timezone' => 'America/Sao_Paulo',
                'default_latitude' => '31.7667',
                'default_longitude' => '35.2333',
            ),
        );
        $iniConfig = array(
            'display_erros' => $originalConfig['display_erros'],
            'date.timezone' => $originalConfig['date']['timezone'],
            'date.default_latitude' => $originalConfig['date']['default_latitude'],
            'date.default_longitude' => $originalConfig['date']['default_longitude'],
        );

        new Php($originalConfig, new Bootstrap(array(), 'test'));

        $this->assertSame($iniConfig, $GLOBALS['ini_set']);
    }

    /**
     * @covers Arara\Boot\Provider\Php::__invoke
     */
    public function testShouldRetrieveInstanceWhenInvoked()
    {
        $provider = new Php(array(), new Bootstrap(array(), 'test'));

        $this->assertSame($provider, $provider());
    }

}
