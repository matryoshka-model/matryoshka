<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

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
use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\HydratorPluginManager;
use Zend\Hydrator\ObjectProperty;
use Matryoshka\Model\Object\PrototypeStrategy\CloneStrategy;
use MatryoshkaTest\Model\TestAsset\ListenerAggregate;
use Matryoshka\Model\Listener\ListenerManager;

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
            'matryoshka-models' => [
                'MyModel\A' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                ],
                'MyModel\B' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'ArrayObject',
                    'hydrator' => 'Zend\Hydrator\ArraySerializable',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                ],
                'MyModel\O' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'ArrayObject',
                    'hydrator' => 'Zend\Hydrator\ArraySerializable',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyObservableModel',
                    'prototype_strategy' => 'Matryoshka\Model\Object\PrototypeStrategy\CloneStrategy',
                ],
                'MyModel\OL' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'ArrayObject',
                    'hydrator' => 'Zend\Hydrator\ArraySerializable',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyObservableModel',
                    'listeners' => [
                        'ListenerAggregateMockedAsset'
                    ]
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
                'MyModel\InvalidListenerModel' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                    'listeners' => ['\stdClass']
                ],
                'MyModel\InvalidListener' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyObservableModel',
                    'listeners' => ['\stdClass']
                ],
                'MyModel\InvalidPrototypeStrategy' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                    'prototype_strategy' => '\stdClass'
                ],
                'MyModel\Full' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object' => 'DomainObject',
                    'hydrator' => 'Zend\Hydrator\ObjectProperty',
                    'input_filter' => 'Zend\InputFilter\InputFilter',
                    'paginator_criteria' => 'MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria',
                    'type' => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                    'buffered_resultset' => true,
                ],
            ],
        ];

        $sm = $this->serviceManager = new ServiceManager\ServiceManager([
                'abstract_factories' => [
                    'Matryoshka\Model\Service\ModelAbstractServiceFactory',
                ]
            ]
        );

        $sm->setService('Config', $config);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new FakeDataGateway);
        $sm->setService('Matryoshka\Model\Object\PrototypeStrategy\CloneStrategy', new CloneStrategy());
        $sm->setService('Matryoshka\Model\ResultSet\ResultSet', new ResultSet);
        $sm->setService('Matryoshka\Model\ResultSet\HydratingResultSet', new HydratingResultSet);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria', $paginatorCriteria);
        $sm->setService('Zend\Hydrator\ArraySerializable', new ArraySerializable);
        $sm->setService('\stdClass', new \stdClass);
        $sm->setService('ArrayObject', new \ArrayObject);
        $sm->setService('DomainObject', $objectPrototype);
        $sm->setService(
            'ListenerAggregateMockedAsset',
            $this->getMockForAbstractClass('Zend\EventManager\ListenerAggregateInterface', ['attach'])
        );
    }

    /**
     * @return void
     */
    public function testCanCreateService()
    {
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;

        $this->assertFalse($factory->canCreate(
            $serviceLocator,
            'MyModel\NonExistingModel'
        ));
        $this->assertTrue($factory->canCreate($serviceLocator, 'MyModel\A'));
        $this->assertTrue($factory->canCreate($serviceLocator, 'MyModel\B'));
        $this->assertTrue($factory->canCreate($serviceLocator, 'MyModel\O'));
        $this->assertTrue($factory->canCreate($serviceLocator, 'MyModel\Full'));

        //test without config
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = new ServiceManager\ServiceManager([]);

        $this->assertFalse($factory->canCreate(
            $serviceLocator,
            'MyModel\NonExistingModel'
        ));

        // Test with empty config
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator->setService('Config', []);

        $this->assertFalse($factory->canCreate(
            $serviceLocator,
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

        $listenerAggregate = $serviceLocator->get('ListenerAggregateMockedAsset');
        $listenerAggregate->method('attach')
                          ->with($this->isInstanceOf('Zend\EventManager\EventManagerInterface'));

        $modelOL = $serviceLocator->get('MyModel\OL');
        $this->assertInstanceOf('\Matryoshka\Model\ObservableModel', $modelOL);
        // TODO: test that listener (for a given event that have to be set) has been really registered

        $modelO = $serviceLocator->get('MyModel\O');
        $this->assertInstanceOf('\Matryoshka\Model\ObservableModel', $modelO);

        // Register optional services
        $hydrator = new ObjectProperty;
        $serviceLocator->setService('Zend\Hydrator\ObjectProperty', $hydrator);

        $inputFilter = new InputFilter;
        $serviceLocator->setService('Zend\InputFilter\InputFilter', $inputFilter);

        $modelFull = $serviceLocator->get('MyModel\Full');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\MyModel', $modelFull);
        $this->assertInstanceOf('Zend\Hydrator\ObjectProperty', $modelFull->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $modelFull->getInputFilter());
        $this->assertInstanceOf(
            '\MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria',
            $modelFull->getPaginatorCriteria()
        );
        $this->assertInstanceOf(
            '\MatryoshkaTest\Model\Service\TestAsset\DomainObject',
            $modelFull->getObjectPrototype()
        );
        $this->assertInstanceOf(
            '\Matryoshka\Model\ResultSet\BufferedResultSet',
            $modelFull->getResultSetPrototype()
        );
        $this->assertSame(
            $serviceLocator->get('Matryoshka\Model\ResultSet\HydratingResultSet'),
            $modelFull->getResultSetPrototype()->getResultSet()
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
        $hydratorManager = new HydratorPluginManager($serviceLocator);
        $hydratorManager->setService('Zend\Hydrator\ObjectProperty', $hydrator);
        $serviceLocator->setService('HydratorManager', $hydratorManager);

        $inputFilter = new InputFilter;
        $inputFilterManager = new InputFilterPluginManager($serviceLocator);
        $inputFilterManager->setService('Zend\InputFilter\InputFilter', $inputFilter);
        $serviceLocator->setService('InputFilterManager', $inputFilterManager);

        $domainObject = new DomainObject;
        $objectManager = new ObjectManager($serviceLocator);
        $objectManager->setService('DomainObject', $domainObject);
        $serviceLocator->setService(ObjectManager::class, $objectManager);

        $modelFull = $serviceLocator->get('MyModel\Full');
        $this->assertSame($hydrator, $modelFull->getHydrator());
        $this->assertSame($inputFilter, $modelFull->getInputFilter());
        $this->assertSame($domainObject, $modelFull->getObjectPrototype());

        $listenerAggregate = $this->getMockForAbstractClass('Zend\EventManager\ListenerAggregateInterface', ['attach']);
        $listenerAggregate = $serviceLocator->get('ListenerAggregateMockedAsset');
        $listenerAggregate->expects($this->atLeastOnce())
                          ->method('attach')
                          ->with($this->isInstanceOf('Zend\EventManager\EventManagerInterface'));

        $listenerManager = new ListenerManager($serviceLocator);
        $listenerManager->setService('ListenerAggregateMockedAsset', $listenerAggregate);
        $serviceLocator->setService('Matryoshka\Model\Listener\ListenerManager', $listenerManager);

        /* @var $myModelOL \Matryoshka\Model\ObservableModel */
        $myModelOL = $serviceLocator->get('MyModel\OL');
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
        $serviceLocator->setService('Zend\Hydrator\ObjectProperty', $hydrator);

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

        $factory(
            $serviceLocator,
            'MyModel\InvalidTypeModel'
        );
    }

    /**
     * @depends testCreateService
     * @expectedException \Matryoshka\Model\Exception\ServiceNotCreatedException
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidPaginator()
    {
        $serviceLocator = $this->serviceManager;
        $factory = new ModelAbstractServiceFactory();
        $factory(
            $serviceLocator,
            'MyModel\InvalidPaginatorModel'
        );
    }

    /**
     * @depends testCreateService
     * @expectedException \Matryoshka\Model\Exception\ServiceNotCreatedException
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidListenerModel()
    {
        $serviceLocator = $this->serviceManager;
        $factory = new ModelAbstractServiceFactory();
        $factory(
            $serviceLocator,
            'MyModel\InvalidListenerModel'
        );
    }

    /**
     * @depends testCreateService
     * @expectedException \Matryoshka\Model\Exception\ServiceNotCreatedException
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidListener()
    {
        $serviceLocator = $this->serviceManager;
        $factory = new ModelAbstractServiceFactory();
        $factory(
            $serviceLocator,
            'MyModel\InvalidListener'
        );
    }

    /**
     * @depends testCreateService
     * @expectedException \Matryoshka\Model\Exception\RuntimeException
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidPrototypeStrategy()
    {
        $serviceLocator = $this->serviceManager;
        $factory = new ModelAbstractServiceFactory();
        $factory(
            $serviceLocator,
            'MyModel\InvalidPrototypeStrategy'
        );
    }
}
