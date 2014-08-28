<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet\PrototypeStrategy;

use Matryoshka\Model\ResultSet\PrototypeStrategy\CloneStrategy;
use Zend\ServiceManager;
use Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy;
use MatryoshkaTest\Model\Service\TestAsset\DomainObject;
use Zend\Mvc\Service\ServiceManagerConfig;

class ServiceLocatorStrategyTest extends \PHPUnit_Framework_TestCase
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
        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig(array(''))
        );

        $sm->setAllowOverride(true);
        $sm->setShareByDefault(false);

        $sm->setInvokableClass('MyDomainObject', '\MatryoshkaTest\Model\Service\TestAsset\DomainObject');

    }

    public function testCreateObject()
    {
        $strategy = new ServiceLocatorStrategy($this->serviceManager);
        $myDomainObject = $this->serviceManager->get('MyDomainObject');
        $data = array('type' => 'MyDomainObject', 'foo' => 'bar');

        $object = $strategy->createObject($myDomainObject, $data);

        $strategy->setTypeField('type');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObject, $object);
        $this->assertNotSame($myDomainObject, $object);

        $strategy->setCloneObject(true);
        $object = $strategy->createObject($myDomainObject, $data);
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObject, $object);
        $this->assertNotSame($myDomainObject, $object);

        $strategy->setValidateObject(true);
        $object = $strategy->createObject($myDomainObject, $data);
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\DomainObject', $object);
        $this->assertEquals($myDomainObject, $object);
        $this->assertNotSame($myDomainObject, $object);

        $this->setExpectedException('\Matryoshka\Model\Exception\ErrorException');
        $object = $strategy->createObject(new \stdClass(), $data);
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
