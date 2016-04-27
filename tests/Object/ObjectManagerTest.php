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
        $pluginManager = new ObjectManager();
        $pluginManager->setService('samplePlugin', 'thisIsNotAnObject');
        $plugin = $pluginManager->get('samplePlugin');
    }

    public function testCanCreateByObjectAbstractServiceFactory()
    {
        $config = [
            'matryoshka-models' => [
                'MyModel\A' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                ],
                'MyModel\O' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyObservableModel',
                ],
            ],
        ];

        $config = [
            'matryoshka-objects' => [
                'MyObject\A' => [
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\DomainObject',
                ],
            ],
        ];


        $services = new ServiceManager();
        $services->setService('Config', $config);

        $pluginManager = new ObjectManager();
        $pluginManager->setServiceLocator($services);
        $objectA = $pluginManager->get('MyObject\A');
        $this->assertInstanceOf('MatryoshkaTest\Model\Service\TestAsset\DomainObject', $objectA);
    }
}
