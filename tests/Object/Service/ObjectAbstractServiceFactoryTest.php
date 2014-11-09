<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object\Service;

use Matryoshka\Model\Model;
use Matryoshka\Model\Object\Service\ObjectAbstractServiceFactory;
use Matryoshka\Model\ResultSet\HydratingResultSet;
use MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria;
use MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Matryoshka\Model\ModelManager;
use Matryoshka\Model\Object\ObjectManager;

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
            new FakeDataGateway,
            new HydratingResultSet
        );

        $config = [
            'matryoshka-objects' => [
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
                ],
                'MyObject\InvalidObjectType' => [
                    'type'        => 'NonExistingClass',
                ],
                'MyObject\InvalidCriteriaType' => [
                    'type'        => 'MatryoshkaTest\Model\TestAsset\ActiveRecordObject',
                    'active_record_criteria' => 'stdClass',
                ],
                'MyObject\Full' => [
                    'type'        => 'MatryoshkaTest\Model\TestAsset\ActiveRecordObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ObjectProperty',
                    'input_filter'=> 'Zend\InputFilter\InputFilter',
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

        $sm->setService('Zend\Stdlib\Hydrator\ObjectProperty', new ObjectProperty);
        $sm->setService('Zend\Stdlib\Hydrator\ArraySerializable', new ArraySerializable);
        $sm->setService('Zend\InputFilter\InputFilter', new InputFilter);
        $sm->setService('TestModel', $this->model);
        $sm->setService('stdClass', new \stdClass);
        $sm->setService('MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria', new ConcreteCriteria);

    }

    /**
     * @return void
     */
    public function testCanCreateService()
    {
        $factory = new ObjectAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;

        $this->assertFalse($factory->canCreateServiceWithName(
            $serviceLocator,
            'myobjectnonexistingobject',
            'MyObject\NonExistingObject'
        ));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjecta', 'MyObject\A'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjectb', 'MyObject\B'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjectc', 'MyObject\C'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'myobjectfull', 'MyObject\Full'));

        //Test without config
        $factory = new ObjectAbstractServiceFactory();
        $serviceLocator = new ServiceManager\ServiceManager(
            new ServiceManagerConfig()
        );

        $this->assertFalse($factory->canCreateServiceWithName(
            $serviceLocator,
            'myobjectnonexistingobject',
            'MyObject\NonExistingObject'
        ));

        //Test with empty config
        $factory = new ObjectAbstractServiceFactory();
        $serviceLocator->setService('Config', []);

        $this->assertFalse($factory->canCreateServiceWithName(
            $serviceLocator,
            'myobjectnonexistingobject',
            'MyObject\NonExistingObject'
        ));
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

        $objectFull = $serviceLocator->get('MyObject\Full');
        $this->assertInstanceOf('\MatryoshkaTest\Model\TestAsset\ActiveRecordObject', $objectFull);
        $this->assertSame($serviceLocator->get('Zend\Stdlib\Hydrator\ObjectProperty'), $objectFull->getHydrator());
        $this->assertSame($serviceLocator->get('Zend\InputFilter\InputFilter'), $objectFull->getInputFilter());
        $this->assertAttributeEquals(
            $serviceLocator->get('MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria'),
            'activeRecordCriteriaPrototype',
            $objectFull);
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

        $objectFull = $serviceLocator->get('MyObject\Full');
        $this->assertSame($hydrator, $objectFull->getHydrator());
        $this->assertSame($inputFilter, $objectFull->getInputFilter());
    }

    /**
     * @expectedException \Matryoshka\Model\Exception\ServiceNotCreatedException
     */
    public function testInvalidActiveRecordCriteria()
    {
        $serviceLocator = $this->serviceManager;

        $factory = new ObjectAbstractServiceFactory();
        $factory->createServiceWithName(
            $serviceLocator,
            'myobjectinvalidcriteriattype',
            'MyObject\InvalidCriteriaType'
        );
    }

    /**
     * @expectedException \Matryoshka\Model\Exception\RuntimeException
     */
    public function testInvalidNonExistingClass()
    {
        $serviceLocator = $this->serviceManager;

        $factory = new ObjectAbstractServiceFactory();
        $factory->createServiceWithName($serviceLocator, 'myobjectinvalidobjecttype', 'MyObject\InvalidObjectType');
    }


    public function testWithObjectManagerPeeringServiceManager()
    {
        $serviceLocator = $this->serviceManager;
        $objectManager = new ObjectManager();
        $objectManager->setServiceLocator($serviceLocator);

        $objectFull = $objectManager->get('MyObject\Full');
        $this->assertInstanceOf('\MatryoshkaTest\Model\TestAsset\ActiveRecordObject', $objectFull);
        $this->assertSame($serviceLocator->get('Zend\Stdlib\Hydrator\ObjectProperty'), $objectFull->getHydrator());
        $this->assertSame($serviceLocator->get('Zend\InputFilter\InputFilter'), $objectFull->getInputFilter());
        $this->assertAttributeEquals(
            $serviceLocator->get('MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria'),
            'activeRecordCriteriaPrototype',
            $objectFull);
    }
}
