<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Hydrator\Strategy\HasManyStrategy;

class HasManyStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testGetHydrator()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasManyStrategy($abstractObject);
        $this->assertSame($abstractObject, $strategy->getObjectPrototype());
    }

    public function testHydrate()
    {
        $abstractObject = $this->getMockForAbstractClass('\Matryoshka\Model\Object\AbstractObject');
        $strategy = new HasManyStrategy($abstractObject);

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
        $strategy = new HasManyStrategy($abstractObject);

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
