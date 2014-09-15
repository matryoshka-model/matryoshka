<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\ModelManager;
use Matryoshka\Model\ResultSet\ArrayObjectResultSet as ResultSet;
use MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ModelManagerTest
 */
class ModelManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testPluginManagerThrowsExceptionForMissingPluginInterface()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\InvalidPluginException');
        $pluginManager = new ModelManager();
        $pluginManager->setInvokableClass('samplePlugin', 'stdClass');
        $plugin = $pluginManager->get('samplePlugin');
    }

    public function testCanCreateByModelAbstractServiceFactory()
    {
        $config = [
            'model' => [
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

        $services = new ServiceManager();
        $services->setService('Config', $config);
        $services->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new FakeDataGateway);
        $services->setService('Matryoshka\Model\ResultSet\ResultSet', new ResultSet);

        $pluginManager = new ModelManager();
        $pluginManager->setServiceLocator($services);
        $modelA = $pluginManager->get('MyModel\A');
        $this->assertInstanceOf('Matryoshka\Model\Model', $modelA);

        $modelO = $pluginManager->get('MyModel\O');
        $this->assertInstanceOf('Matryoshka\Model\ObservableModel', $modelO);
    }
}
