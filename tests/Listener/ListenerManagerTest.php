<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Listener;

use Matryoshka\Model\Listener\ListenerManager;
use Zend\ServiceManager\ServiceManager;
use MatryoshkaTest\Model\TestAsset\ListenerAggregate;

/**
 * Class ListenerManagerTest
 */
class ListenerManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testPluginManagerThrowsExceptionForInvalidPluginType()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\InvalidPluginException');
        $pluginManager = new ListenerManager();
        $pluginManager->setService('samplePlugin', 'thisIsNotAnListener');
        $plugin = $pluginManager->get('samplePlugin');
    }

    public function testPluginManagerThrowsExceptionForInvalidPluginInterface()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\InvalidPluginException');
        $pluginManager = new ListenerManager();
        $pluginManager->setService('samplePlugin', new \stdClass());
        $plugin = $pluginManager->get('samplePlugin');
    }

    public function testValidatePlugin()
    {
        $pluginManager = new ListenerManager();
        $pluginManager->validatePlugin(new ListenerAggregate());
    }
}
