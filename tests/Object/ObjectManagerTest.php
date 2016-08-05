<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object;

use Matryoshka\Model\Object\ObjectManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ObjectManagerTest
 */
class ObjectManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testPluginManagerThrowsExceptionForMissingPluginInterface()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\InvalidPluginException');
        $pluginManager = new ObjectManager(new ServiceManager());
        $pluginManager->setService('samplePlugin', 'thisIsNotAnObject');
        $plugin = $pluginManager->get('samplePlugin');
    }

    public function testCanCreateByObjectAbstractServiceFactory()
    {
        $config = [
            'invokables' => [
                'MyObject\A' => 'MatryoshkaTest\Model\Service\TestAsset\DomainObject',
            ],
        ];

        $services = new ServiceManager();
        $services->setService('Config', $config);

        $pluginManager = new ObjectManager($services, $config);
        $objectA = $pluginManager->get('MyObject\A');
        $this->assertInstanceOf('MatryoshkaTest\Model\Service\TestAsset\DomainObject', $objectA);
    }
}
