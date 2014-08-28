<?php
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Hydrator\Strategy\HasMany;
class HasManyTest extends \PHPUnit_Framework_TestCase
{

    public function testGetHydrator()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasMany($abstractObject);
        $this->assertSame($abstractObject, $strategy->getObjectPrototype());
    }

    public function testHydrate()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasMany($abstractObject);

        $hydratedValue = $strategy->hydrate([$abstractObject]);
        $this->assertInstanceOf('\ArrayObject', $hydratedValue);
        $this->assertCount(1, $hydratedValue);
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $hydratedValue[0]);

        $hydratedValue = $strategy->hydrate([]);
        $this->assertInstanceOf('\ArrayObject', $hydratedValue);
        $this->assertCount(0, $hydratedValue);

        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $strategy->hydrate('wrong value');
    }

    public function testExtract()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasMany($abstractObject);

        $extractedValue = $strategy->extract(new \ArrayObject([$abstractObject]));
        $this->assertInternalType('array', $extractedValue);
        $this->assertCount(1, $extractedValue);
        $this->assertInternalType('array', $extractedValue[0]);

        $extractedValue = $strategy->extract(new \ArrayObject([]));
        $this->assertInternalType('array', $extractedValue);
        $this->assertCount(0, $extractedValue);

        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $strategy->extract('wrong value');
    }
}