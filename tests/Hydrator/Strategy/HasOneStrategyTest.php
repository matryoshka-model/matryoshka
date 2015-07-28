<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Hydrator\Strategy;

use Matryoshka\Model\Hydrator\Strategy\HasOneStrategy;
use Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyAwareInterface;

/**
 * Class HasOneStrategyTest
 */
class HasOneStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject);
        $this->assertInstanceOf('Matryoshka\Model\Hydrator\Strategy\NullableStrategyInterface', $strategy);
        $this->assertTrue($strategy->isNullable());
    }

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

        $strategy->setNullable(false);
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate($abstractObject));
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate([]));
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate(null));

        $strategy->setNullable(true);
        $this->assertNull($strategy->hydrate(null));

        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $strategy->hydrate('wrong value');
    }

    public function testExtract()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject);

        $strategy->setNullable(false);
        $this->assertInternalType('array', $strategy->extract($abstractObject));
        $this->assertInternalType('array', $strategy->extract([]));
        $this->assertInternalType('array', $strategy->extract(null));

        $strategy->setNullable(true);
        $this->assertNull($strategy->extract(null));

        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $strategy->extract('wrong value');
    }

    public function testPrototypeStrategy()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject);

        $this->assertInstanceOf('\Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyAwareInterface', $strategy);

        $prototypeStrategy = $this->getMockForAbstractClass(
            '\Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyInterface'
        );

        $prototypeStrategy->expects($this->atLeastOnce())
            ->method('createObject')
            ->with($this->equalTo($abstractObject))
            ->willReturn($abstractObject);

        $strategy->setPrototypeStrategy($prototypeStrategy);
        $this->assertInstanceOf('\Matryoshka\Model\Object\AbstractObject', $strategy->hydrate([]));

    }
}
