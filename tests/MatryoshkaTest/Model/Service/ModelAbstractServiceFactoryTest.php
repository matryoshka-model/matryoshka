<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

use Matryoshka\Model\Service\ModelAbstractServiceFactory;
use Zend\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

/**
 * Class ModelAbstractServiceFactoryTest
 */
class ModelAbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @return void
     */
    public function setUp()
    {
        $dataGateway = new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
        $resultSet   = new \Matryoshka\Model\ResultSet\ResultSet;

        $config = array(
            'model' => array(
                'MyModel\A' => array(
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\ResultSet',
                ),
                'MyModel\B' => array(
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\ResultSet',
                ),
            ),
        );

        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig(array(
                'abstract_factories' => array(
                    'Matryoshka\Model\Service\ModelAbstractServiceFactory',
                )
            ))
        );

        $sm->setService('Config', $config);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', $dataGateway);
        $sm->setService('Matryoshka\Model\ResultSet\ResultSet', $resultSet);
    }


    /**
     * @return void
     */
    public function testCanCreateService()
    {
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'mymodelnonexistingmodel', 'MyModel\NonExistingModel'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodela', 'MyModel\A'));
    }

    /**
     * @depends testCanCreateService
     * @return void
     */
    public function testCreateService()
    {
        $serviceLocator = $this->serviceManager;

        $this->assertInstanceOf('Matryoshka\Model\Model', $serviceLocator->get('MyModel\A'));
        $modelA = $serviceLocator->get('MyModel\A');
    }
}