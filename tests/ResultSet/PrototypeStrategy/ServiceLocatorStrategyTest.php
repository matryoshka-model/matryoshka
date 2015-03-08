<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet\PrototypeStrategy;

use Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager;
use MatryoshkaTest\Model\Service\TestAsset\DomainObject;

/**
 * Class ServiceLocatorStrategyTest
 */
class ServiceLocatorStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    protected $modelMock;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->modelMock = $this->getMock(
            '\Matryoshka\Model\ModelInterface'
        );

        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig([''])
        );

        $sm->setAllowOverride(true);
        $sm->setShareByDefault(false);

        $sm->setInvokableClass('MyDomainObject', '\MatryoshkaTest\Model\Service\TestAsset\DomainObject');

        $obj = new DomainObject();
        $obj->setModel($this->modelMock);
        $sm->setService('MyDomainObjectWithModel', $obj);

    }

    public function testCreateObject()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $myDomainObject = $this->serviceManager->get('MyDomainObject');
        $myDomainObjectWithModel = $this->serviceManager->get('MyDomainObjectWithModel');
        $data = ['type' => 'MyDomainObject', 'foo' => 'bar'];

        $object = $strategy->createObject($myDomainObject, $data);

        $strategy->setTypeField('type');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObject, $object);
        $this->assertNotSame($myDomainObject, $object);
        $this->assertNull($object->getModel());

        $strategy->setCloneObject(true);
        $object = $strategy->createObject($myDomainObject, $data);
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObject, $object);
        $this->assertNotSame($myDomainObject, $object);
        $this->assertNull($object->getModel());

        $strategy->setValidateObject(true);
        $object = $strategy->createObject($myDomainObject, $data);
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObject, $object);
        $this->assertNotSame($myDomainObject, $object);
        $this->assertNull($object->getModel());


        $strategy->setValidateObject(true);
        $object = $strategy->createObject($myDomainObjectWithModel, $data);
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObjectWithModel, $object);
        $this->assertNotSame($myDomainObjectWithModel, $object);
        $this->assertSame($object->getModel(), $this->modelMock);


        $this->setExpectedException('\Matryoshka\Model\Exception\ErrorException');
        $object = $strategy->createObject(new \stdClass(), $data);
    }

    public function testCreateShouldThrowExceptionWhenFieldIsNotPresent()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $myDomainObject = $this->serviceManager->get('MyDomainObject');
        $data = ['foo' => 'bar'];

        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $strategy->createObject($myDomainObject, $data);
    }

    public function testGetSetServiceLocator()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $strategy->setServiceLocator($this->serviceManager);
        $this->assertSame($this->serviceManager, $strategy->getServiceLocator());
    }

    public function testGetSetTypeField()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $strategy->setTypeField('foo');
        $this->assertSame('foo', $strategy->getTypeField());
    }

    public function testGetSetValidateObject()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $strategy->setValidateObject(true);
        $this->assertSame(true, $strategy->getValidateObject());
    }

    public function testGetSetCloneObject()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $strategy->setCloneObject(true);
        $this->assertSame(true, $strategy->getCloneObject());
    }
}
