<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object\Service;

use Matryoshka\Model\ResultSet\ArrayObjectResultSet as ResultSet;
use MatryoshkaTest\Model\Service\TestAsset\DomainObject;
use MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager;
use Zend\Mvc\Service\HydratorManagerFactory;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\InputFilter\InputFilterPluginManager;
use Matryoshka\Model\Model;
use MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria;
use Matryoshka\Model\Object\Service\ObjectAbstractServiceFactory;

/**
 * Class ObjectAbstractServiceFactoryTest
 */
class ObjectAbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    protected $model;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->model = new Model(
            new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway,
            new \Matryoshka\Model\ResultSet\HydratingResultSet
        );

        $config = [
            'object' => [
                'MyObject\A' => [
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\DomainObject',
                ],
                'MyObject\B' => [
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\DomainObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ObjectProperty',
                    'input_filter'=> 'Zend\InputFilter\InputFilter',
                ],
                'MyObject\C' => [
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\DomainObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ObjectProperty',
                    'input_filter'=> 'Zend\InputFilter\InputFilter',
                    'model'       => 'TestModel',
                ],
                'MyObject\Full' => [
                    'type'        => 'MatryoshkaTest\Model\TestAsset\ActiveRecordObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ObjectProperty',
                    'input_filter'=> 'Zend\InputFilter\InputFilter',
                    'model'       => 'TestModel',
                    'active_record_criteria' => 'MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria',
                ],
            ],
        ];

        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig([
                'abstract_factories' => [
                    'Matryoshka\Model\Object\Service\ObjectAbstractServiceFactory',
                ]
            ])
        );

        $sm->setService('Config', $config);

        $sm->setService('Zend\Stdlib\Hydrator\ObjectProperty', new \Zend\Stdlib\Hydrator\ObjectProperty);
        $sm->setService('Zend\Stdlib\Hydrator\ArraySerializable', new \Zend\Stdlib\Hydrator\ArraySerializable);
        $sm->setService('Zend\InputFilter\InputFilter', new \Zend\InputFilter\InputFilter);
        $sm->setService('TestModel', $this->model);
        $sm->setService('MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria', new ConcreteCriteria);

    }

    /**
     * @return void
     */
    public function testCanCreateService()
    {
        $factory = new ObjectAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'myobjectnonexistingobject', 'MyObject\NonExistingObject'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjecta', 'MyObject\A'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjectb', 'MyObject\B'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjectc', 'MyObject\C'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjectfull', 'MyObject\Full'));

        //test without config
        $factory = new ObjectAbstractServiceFactory();
        $serviceLocator = new ServiceManager\ServiceManager(
            new ServiceManagerConfig()
        );

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'myobjectnonexistingobject', 'MyObject\NonExistingObject'));

        //test with empty config
        $factory = new ObjectAbstractServiceFactory();
        $serviceLocator->setService('Config', []);

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'myobjectnonexistingobject', 'MyObject\NonExistingObject'));
    }

    /**
     * @depends testCanCreateService
     * @return void
     */
    public function testCreateService()
    {
        $serviceLocator = $this->serviceManager;

        $objectA = $serviceLocator->get('MyObject\A');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $objectA);

        $objectB = $serviceLocator->get('MyObject\B');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $objectB);
        $this->assertSame($serviceLocator->get('Zend\Stdlib\Hydrator\ObjectProperty'), $objectB->getHydrator());
        $this->assertSame($serviceLocator->get('Zend\InputFilter\InputFilter'), $objectB->getInputFilter());

        $objectC = $serviceLocator->get('MyObject\C');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $objectC);
        $this->assertSame($serviceLocator->get('Zend\Stdlib\Hydrator\ObjectProperty'), $objectC->getHydrator());
        $this->assertSame($serviceLocator->get('Zend\InputFilter\InputFilter'), $objectC->getInputFilter());
        $this->assertSame($serviceLocator->get('TestModel'), $objectC->getModel());

        $objectFull = $serviceLocator->get('MyObject\Full');
        $this->assertInstanceOf('\MatryoshkaTest\Model\TestAsset\ActiveRecordObject', $objectFull);
        $this->assertSame($serviceLocator->get('Zend\Stdlib\Hydrator\ObjectProperty'), $objectFull->getHydrator());
        $this->assertSame($serviceLocator->get('Zend\InputFilter\InputFilter'), $objectFull->getInputFilter());
        $this->assertSame($serviceLocator->get('TestModel'), $objectFull->getModel());
        $this->assertSame($serviceLocator->get('MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria'), $objectFull->getActiveRecordCriteriaPrototype());
    }

    /**
     * @depends testCanCreateService
     * @return void
     */
    public function testCreateServiceWithOptionalManagers()
    {
        $serviceLocator = $this->serviceManager;

        //Test with optional managers

        $hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty;
        $hydratorManager = new HydratorPluginManager();
        $hydratorManager->setService('Zend\Stdlib\Hydrator\ObjectProperty', $hydrator);
        $serviceLocator->setService('HydratorManager', $hydratorManager);

        $inputFilter = new \Zend\InputFilter\InputFilter;
        $inputFilterManager = new InputFilterPluginManager();
        $inputFilterManager->setService('Zend\InputFilter\InputFilter', $inputFilter);
        $serviceLocator->setService('InputFilterManager', $inputFilterManager);

        $objectFull = $serviceLocator->get('MyObject\Full');
        $this->assertSame($hydrator, $objectFull->getHydrator());
        $this->assertSame($inputFilter, $objectFull->getInputFilter());
    }

}
