<?php
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Hydrator\Strategy\HasOneStrategy;
class HasOneStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testGetHydrator()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject);
        $this->assertSame($abstractObject, $strategy->getObjectPrototype());
    }

    public function testHydrate()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject);

        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate($abstractObject));
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate([]));
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate(null));

        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $strategy->hydrate('wrong value');
    }

    public function testExtract()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject);

        $this->assertInternalType('array', $strategy->extract($abstractObject));
        $this->assertInternalType('array', $strategy->extract([]));
        $this->assertInternalType('array', $strategy->extract(null));

        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $strategy->extract('wrong value');
    }
}