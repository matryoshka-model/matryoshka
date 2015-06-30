<?php
namespace MatryoshkaTest\Model\Object;

/**
 * Class AbstractObjectTest
 */
class AbstractObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHydrator()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $this->assertInstanceOf('\Matryoshka\Model\Hydrator\ClassMethods', $abstractObject->getHydrator());
    }

    public function testGetInputFilter()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $this->assertInstanceOf('\Zend\InputFilter\InputFilter', $abstractObject->getInputFilter());
    }
}
