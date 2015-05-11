<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

use Matryoshka\Model\Service\AbstractServiceFactoryTrait;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\Hydrator\ObjectProperty;

/**
 * Class AbstractServiceFactoryTraitTest
 */
class AbstractServiceFactoryTraitTest extends \PHPUnit_Framework_TestCase
{
    use AbstractServiceFactoryTrait;

    private $config;

    private $configKey;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $serviceLocatorMock;

    public function setUp()
    {
        $this->config = null;
        $this->configKey = 'test';

        $this->serviceLocatorMock = $this->getMockBuilder('\Zend\ServiceManager\ServiceLocatorInterface')
            ->setMethods(['has', 'get'])
            ->getMock();
    }

    public function testGetHydratorByNameWithoutHydratorManager()
    {
        $name = 'Zend\Stdlib\Hydrator\ObjectProperty';
        $hydrator = new $name;

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('HydratorManager')
            ->will($this->returnValue(false));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($hydrator));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test valid hydrator type
        $return = $this->getHydratorByName($serviceLocator, $name);
        $this->assertSame($hydrator, $return);
    }

    public function testGetHydratorByNameWithoutHydratorManagerShouldThrowExceptionWhenNotValidHydrator()
    {
        $name = '\stdClass';
        $hydrator = new $name;

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('HydratorManager')
            ->will($this->returnValue(false));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($hydrator));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test invalid hydrator tupe
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->getHydratorByName($serviceLocator, $name);
    }

    public function testGetHydratorByNameWithHydratorManager()
    {
        $name = 'Zend\Stdlib\Hydrator\ObjectProperty';
        $hydrator = new $name;
        $hydratorManagerMock = $this->getMockBuilder('Zend\Stdlib\Hydrator\HydratorPluginManager')
            ->setMethods(['get'])
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('HydratorManager')
            ->will($this->returnValue(true));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('HydratorManager')
            ->will($this->returnValue($hydratorManagerMock));

        $hydratorManagerMock->expects($this->at(0))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($hydrator));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test valid hydrator type
        $return = $this->getHydratorByName($serviceLocator, $name);
        $this->assertSame($hydrator, $return);
    }

    public function testGetHydratorByNameWithHydratorManagerShouldThrowExceptionWhenHydratorNotValid()
    {
        $name = '\stdClass';
        $hydrator = new $name;
        $hydratorManagerMock = $this->getMockBuilder('Zend\Stdlib\Hydrator\HydratorPluginManager')
            ->setMethods(['get'])
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('HydratorManager')
            ->will($this->returnValue(true));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('HydratorManager')
            ->will($this->returnValue($hydratorManagerMock));

        $hydratorManagerMock->expects($this->at(0))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($hydrator));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test invalid hydrator tupe
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->getHydratorByName($serviceLocator, $name);
    }

    public function testGetInputFilterByNameWithoutInputFilterManager()
    {
        $name = 'Zend\InputFilter\InputFilter';
        $inputFilter = new $name;

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('InputFilterManager')
            ->will($this->returnValue(false));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($inputFilter));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test valid InputFilter type
        $return = $this->getInputFilterByName($serviceLocator, $name);
        $this->assertSame($inputFilter, $return);
    }

    public function testGetInputFilterByNameWithoutInputFilterManagerShouldThrowExceptionWhenNotValidInputFilter()
    {
        $name = '\stdClass';
        $inputFilter = new $name;

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('InputFilterManager')
            ->will($this->returnValue(false));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($inputFilter));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test invalid InputFilter tupe
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->getInputFilterByName($serviceLocator, $name);
    }

    public function testGetInputFilterByNameWithInputFilterManager()
    {
        $name = 'Zend\InputFilter\InputFilter';
        $inputFilter = new $name;
        $inputFilterManagerMock = $this->getMockBuilder('Zend\InputFilter\InputFilterPluginManager')
            ->setMethods(['get'])
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('InputFilterManager')
            ->will($this->returnValue(true));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('InputFilterManager')
            ->will($this->returnValue($inputFilterManagerMock));

        $inputFilterManagerMock->expects($this->at(0))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($inputFilter));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test valid InputFilter type
        $return = $this->getInputFilterByName($serviceLocator, $name);
        $this->assertSame($inputFilter, $return);
    }

    public function testGetInputFilterByNameWithInputFilterManagerShouldThrowExceptionWhenInputFilterNotValid()
    {
        $name = '\stdClass';
        $inputFilter = new $name;
        $inputFilterManagerMock = $this->getMockBuilder('Zend\InputFilter\InputFilterPluginManager')
            ->setMethods(['get'])
            ->getMock();

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('InputFilterManager')
            ->will($this->returnValue(true));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('InputFilterManager')
            ->will($this->returnValue($inputFilterManagerMock));

        $inputFilterManagerMock->expects($this->at(0))
            ->method('get')
            ->with($name)
            ->will($this->returnValue($inputFilter));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        //Test invalid InputFilter tupe
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->getInputFilterByName($serviceLocator, $name);
    }

    public function testGetConfigWhenNodeConfigDoesNotExists()
    {
        $cfg = [];

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('Config')
            ->will($this->returnValue(false));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        $return = $this->getConfig($serviceLocator);
        $this->assertSame($cfg, $return);
    }

    public function testGetConfigWhenNodeConfigExists()
    {
        $cfg = [
            $this->configKey => ['test']
        ];

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('Config')
            ->will($this->returnValue(true));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($cfg));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        $return = $this->getConfig($serviceLocator);
        $this->assertSame($cfg[$this->configKey], $return);
    }

    public function testGetConfigWhenNodeConfigExistsButIsNotValid()
    {
        $cfg = [
            $this->configKey => 'string'
        ];

        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('Config')
            ->will($this->returnValue(true));

        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($cfg));

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        $return = $this->getConfig($serviceLocator);
        $this->assertSame([], $return);
    }

    public function testGetConfigWhenConfigFieldNotNull()
    {
        $this->config = [];

        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;
        $return = $this->getConfig($serviceLocator);
        $this->assertSame($this->config, $return);
    }
}
