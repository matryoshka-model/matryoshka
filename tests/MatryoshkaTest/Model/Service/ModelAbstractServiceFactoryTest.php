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
                    'resultset'   => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object'      => 'ArrayObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                ),
                'MyModel\InvalidTypeModel' => array(
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type'        => '\stdClass',
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
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway);
        $sm->setService('Matryoshka\Model\ResultSet\ResultSet', new \Matryoshka\Model\ResultSet\ResultSet);
        $sm->setService('Matryoshka\Model\ResultSet\HydratingResultSet', new \Matryoshka\Model\ResultSet\HydratingResultSet);
        $sm->setService('Zend\Stdlib\Hydrator\ArraySerializable', new \Zend\Stdlib\Hydrator\ArraySerializable);
        $sm->setService('ArrayObject', new \ArrayObject);
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

        $modelA = $serviceLocator->get('MyModel\A');
        $this->assertInstanceOf('\Matryoshka\Model\Model', $modelA);


        $modelB = $serviceLocator->get('MyModel\B');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\MyModel', $modelB);
    }

    /**
     * @depends testCreateService
     * @return void
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidType()
    {
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;
        $this->setExpectedException('\Matryoshka\Model\Exception\UnexpectedValueException');

        $factory->createServiceWithName($serviceLocator, 'mymodelinvalidtypemodel', 'MyModel\InvalidTypeModel');
    }
}