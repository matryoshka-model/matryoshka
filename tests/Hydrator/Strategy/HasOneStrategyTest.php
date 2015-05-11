<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Hydrator\Strategy;

use Matryoshka\Model\Hydrator\Strategy\HasOneStrategy;

class HasOneStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasOneStrategy($abstractObject, new \ArrayObject());
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
}
