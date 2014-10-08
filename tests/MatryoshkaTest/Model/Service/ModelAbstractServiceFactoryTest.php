<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

use Matryoshka\Model\Exception\RuntimeException;
use Matryoshka\Model\Object\ObjectManager;
use Matryoshka\Model\ResultSet\ArrayObjectResultSet as ResultSet;
use Matryoshka\Model\ResultSet\HydratingResultSet;
use Matryoshka\Model\Service\ModelAbstractServiceFactory;
use MatryoshkaTest\Model\Service\TestAsset\DomainObject;
use MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
use MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\Hydrator\ObjectProperty;

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
        $objectPrototype = new DomainObject();
        $paginatorCriteria = new PaginatorCriteria();

        $config = [
            'model' => [
                'MyModel\A' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                ],
                'MyModel\B' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'ArrayObject',
                    'hydrator' => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                ],
                'MyModel\O' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'ArrayObject',
                    'hydrator' => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyObservableModel',
                ],
                'MyModel\InvalidTypeModel' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type' => 'stdClass',
                ],
                'MyModel\InvalidPaginatorModel' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                    'paginator_criteria' => '\stdClass'
                ],
                'MyModel\Full' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'DomainObject',
                    'hydrator' => 'Zend\Stdlib\Hydrator\ObjectProperty',
                    'input_filter' => 'Zend\InputFilter\InputFilter',
                    'paginator_criteria' => 'MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                ],
            ],
        ];

        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig([
                'abstract_factories' => [
                    'Matryoshka\Model\Service\ModelAbstractServiceFactory',
                ]
            ])
        );

        $sm->setService('Config', $config);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new FakeDataGateway);
        $sm->setService('Matryoshka\Model\ResultSet\ResultSet', new ResultSet);
        $sm->setService('Matryoshka\Model\ResultSet\HydratingResultSet', new HydratingResultSet);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria', $paginatorCriteria);
        $sm->setService('Zend\Stdlib\Hydrator\ArraySerializable', new ArraySerializable);
        $sm->setService('stdClass', new \stdClass);
        $sm->setService('ArrayObject', new \ArrayObject);
        $sm->setService('DomainObject', $objectPrototype);
    }

    /**
     * @return void
     */
    public function testCanCreateService()
    {
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;

        $this->assertFalse($factory->canCreateServiceWithName(
            $serviceLocator,
            'mymodelnonexistingmodel',
            'MyModel\NonExistingModel'
        ));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodela', 'MyModel\A'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodelb', 'MyModel\B'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodelo', 'MyModel\O'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodelfull', 'MyModel\Full'));

        //test without config
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = new ServiceManager\ServiceManager(
            new ServiceManagerConfig()
        );

        $this->assertFalse($factory->canCreateServiceWithName(
            $serviceLocator,
            'mymodelnonexistingmodel',
            'MyModel\NonExistingModel'
        ));

        // Test with empty config
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator->setService('Config', []);

        $this->assertFalse($factory->canCreateServiceWithName(
            $serviceLocator,
            'mymodelnonexistingmodel',
            'MyModel\NonExistingModel'
        ));
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

        $modelO = $serviceLocator->get('MyModel\O');
        $this->assertInstanceOf('\Matryoshka\Model\ObservableModel', $modelO);

        // Register optional services
        $hydrator = new ObjectProperty;
        $serviceLocator->setService('Zend\Stdlib\Hydrator\ObjectProperty', $hydrator);

        $inputFilter = new InputFilter;
        $serviceLocator->setService('Zend\InputFilter\InputFilter', $inputFilter);

        $modelFull = $serviceLocator->get('MyModel\Full');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\MyModel', $modelFull);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\ObjectProperty', $modelFull->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $modelFull->getInputFilter());
        $this->assertInstanceOf(
            '\MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria',
            $modelFull->getPaginatorCriteria()
        );
        $this->assertInstanceOf(
            '\MatryoshkaTest\Model\Service\TestAsset\DomainObject',
            $modelFull->getObjectPrototype()
        );
    }

    /**
     * @depends testCanCreateService
     * @return void
     */
    public function testCreateServiceWithOptionalManagers()
    {
        $serviceLocator = $this->serviceManager;

        //Test with optional managers
        $hydrator = new ObjectProperty;
        $hydratorManager = new HydratorPluginManager();
        $hydratorManager->setService('Zend\Stdlib\Hydrator\ObjectProperty', $hydrator);
        $serviceLocator->setService('HydratorManager', $hydratorManager);

        $inputFilter = new InputFilter;
        $inputFilterManager = new InputFilterPluginManager();
        $inputFilterManager->setService('Zend\InputFilter\InputFilter', $inputFilter);
        $serviceLocator->setService('InputFilterManager', $inputFilterManager);

        $domainObject = new DomainObject;
        $objectManager = new ObjectManager();
        $objectManager->setService('DomainObject', $domainObject);
        $serviceLocator->setService('Matryoshka\Model\Object\ObjectManager', $objectManager);

        $modelFull = $serviceLocator->get('MyModel\Full');
        $this->assertSame($hydrator, $modelFull->getHydrator());
        $this->assertSame($inputFilter, $modelFull->getInputFilter());
        $this->assertSame($domainObject, $modelFull->getObjectPrototype());
    }

    /**
     * @depends testCanCreateService
     * @return void
     */
    public function testCreateServiceWithOptionalServices()
    {
        $serviceLocator = $this->serviceManager;

        //Test with optional services
        $hydrator = new ObjectProperty;
        $serviceLocator->setService('Zend\Stdlib\Hydrator\ObjectProperty', $hydrator);

        $inputFilter = new InputFilter;
        $serviceLocator->setService('Zend\InputFilter\InputFilter', $inputFilter);

        $modelFull = $serviceLocator->get('MyModel\Full');
        $this->assertSame($hydrator, $modelFull->getHydrator());
        $this->assertSame($inputFilter, $modelFull->getInputFilter());
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

    /**
     * @depends testCreateService
     * @expectedException RuntimeException
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidPaginator()
    {
        $serviceLocator = $this->serviceManager;
        $factory = new ModelAbstractServiceFactory();
        $factory->createServiceWithName(
            $serviceLocator,
            'mymodelinvalidpaginatormodel',
            'MyModel\InvalidPaginatorModel'
        );
    }
}
