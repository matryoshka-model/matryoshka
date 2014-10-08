<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

use Matryoshka\Model\Service\AbstractServiceFactoryTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;

/**
 * Class AbstractServiceFactoryTraitTest
 */
class AbstractServiceFactoryTraitTest extends \PHPUnit_Framework_TestCase
{
    use AbstractServiceFactoryTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $serviceLocatorMock;

    public function setUp()
    {
        $this->serviceLocatorMock = $this->getMockBuilder('\Zend\ServiceManager\ServiceLocatorInterface')
            ->setMethods(['has', 'get'])
            ->getMock();
    }

    public function testGetHydratorByNameWithoutHydratorManager()
    {
        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('HydratorManager')
            ->will($this->returnValue(false));
        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;

        $hydrator = new ObjectProperty;
        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('Zend\Stdlib\Hydrator\HydratorInterface')
            ->will($this->returnValue($hydrator));

        //Test valid hydrator type
        $return = $this->getHydratorByName($serviceLocator, 'Zend\Stdlib\Hydrator\HydratorInterface');
        $this->assertSame($hydrator, $return);
    }

    public function testGetHydratorByNameWithourHydratorManagerShouldThrowExceptionWhenNotValidHydrator()
    {
        $this->serviceLocatorMock->expects($this->at(0))
            ->method('has')
            ->with('HydratorManager')
            ->will($this->returnValue(false));
        /** @var $serviceLocator ServiceLocatorInterface */
        $serviceLocator = $this->serviceLocatorMock;

        $hydrator = new \stdClass;
        $this->serviceLocatorMock->expects($this->at(1))
            ->method('get')
            ->with('stdClass')
            ->will($this->returnValue($hydrator));

        //Test invalid hydrator tupe
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->getHydratorByName($serviceLocator, 'stdClass');
    }


//    public function testGetInputFilterByName()
//    {
//
//    }
} 