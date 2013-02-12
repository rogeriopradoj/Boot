<?php

namespace Jam\Bootstrapper\Provider;

$GLOBALS['ini_set'] = array();

function ini_set($varname, $newvalue)
{
    $GLOBALS['ini_set'][$varname] = $newvalue;
}

use Jam\Bootstrapper\Bootstrap;

class PhpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Jam\Bootstrapper\Provider\Php::__construct
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
        $boostrap = new Bootstrap(array(), 'test');
        $provider = new Php($originalConfig, $boostrap);

        $this->assertAttributeSame($originalConfig, 'config', $provider);
        $this->assertSame($iniConfig, $GLOBALS['ini_set']);
    }
    /**
     * @covers Jam\Bootstrapper\Provider\Php::__invoke
     */
    public function testShouldRetrieveConfigInvoked()
    {
        $config = array(
            'display_erros' => false,
            'date' => array(
                'timezone' => 'America/Sao_Paulo',
                'default_latitude' => '31.7667',
                'default_longitude' => '35.2333',
            ),
        );
        $provider = new Php($config, new Bootstrap(array(), 'test'));

        $this->assertSame($config, $provider());
    }

}
