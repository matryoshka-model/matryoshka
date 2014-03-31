<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;


use Matryoshka\Model\ModelManager;
use Zend\ServiceManager\ServiceManager;


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
        $dataGateway = new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
        $resultSet   = new \Matryoshka\Model\ResultSet\ResultSet;

        $config = array(
            'model' => array(
                'MyModel\A' => array(
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\ResultSet',
                ),
            ),
        );

        $services = new ServiceManager();
        $services->setService('Config', $config);
        $services->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway);
        $services->setService('Matryoshka\Model\ResultSet\ResultSet', new \Matryoshka\Model\ResultSet\ResultSet);


        $pluginManager = new ModelManager();
        $pluginManager->setServiceLocator($services);
        $model = $pluginManager->get('MyModel\A');

        $this->assertInstanceOf('Matryoshka\Model\Model', $model);
    }
}
