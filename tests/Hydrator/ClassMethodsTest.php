<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Hydrator;

use Matryoshka\Model\Hydrator\ClassMethods;

/**
 * Unit tests for {@see \Matryoshka\Model\Hydrator\ClassMethods}
 *
 * @covers \Matryoshka\Model\Hydrator\ClassMethods
 */
class ClassMethodsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMethods
     */
    protected $hydrator;

    protected $mockObject;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrator = new ClassMethods();
        $this->mockObject = $this->getMock('\Matryoshka\Model\Object\AbstractObject', ['getModel']);
        $this->mockObject->expects($this->any())->method('getModel')->will($this->returnValue(null));
    }

    public function test__constructor()
    {
        $this->assertFalse($this->hydrator->getUnderscoreSeparatedKeys());
    }

    /**
     * Verifies that extraction strips declared methods
     */
    public function testMethodsAreExcludedFromExtraction()
    {
        $result = $this->hydrator->extract($this->mockObject);
        $this->assertArrayNotHasKey('model', $result);
        $this->assertArrayNotHasKey('hydrator', $result);
        $this->assertArrayNotHasKey('inputFilter', $result);
    }
}
