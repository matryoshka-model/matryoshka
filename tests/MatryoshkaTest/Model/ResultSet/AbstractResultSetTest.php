<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet;

use MatryoshkaTest\Model\ResultSet\TestAsset\GenericResultSet;
use MatryoshkaTest\Model\ResultSet\TestAsset\ItemWithToArray;

/**
 * Class AbstractResultSetTest
 */
class AbstractResultSetTest extends \PHPUnit_Framework_TestCase
{

    public function testInitialize()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');

        $data = [
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ];

        $this->assertSame($resultSet, $resultSet->initialize($data));

        //Test accepts IteratorAggregate
        $resultSet->initialize(new \ArrayObject($data));

        //Test accepts Iterator
        $resultSet->initialize(new \ArrayIterator($data));

        $this->setExpectedException(
            '\Matryoshka\Model\Exception\InvalidArgumentException'
        );
        $resultSet->initialize('foo');
    }

    public function testGetDataSource()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertInstanceOf('\ArrayIterator', $resultSet->getDataSource());
    }

    public function testNext()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertNull($resultSet->next());
    }

    public function testKey()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $resultSet->next();
        $this->assertEquals(1, $resultSet->key());
        $resultSet->next();
        $this->assertEquals(2, $resultSet->key());
        $resultSet->next();
        $this->assertEquals(3, $resultSet->key());
    }

    public function testCurrent()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertEquals(['id' => 1, 'name' => 'one'], $resultSet->current());
    }

    public function testValid()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertTrue($resultSet->valid());
        $resultSet->next(); $resultSet->next(); $resultSet->next();
        $this->assertFalse($resultSet->valid());
    }

    public function testRewind()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertNull($resultSet->rewind());
    }

    public function testCount()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertEquals(3, $resultSet->count());
        $this->assertEquals(3, $resultSet->count());
    }

    public function testToArray()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');
        $resultSet->initialize(new \ArrayIterator([
            new \ArrayObject(['id' => 1, 'name' => 'one']), //test cast with getArrayCopy()
            new ItemWithToArray(['id' => 2, 'name' => 'two']), //test cast with toArray()
            ['id' => 3, 'name' => 'three'],
        ]));
        $this->assertEquals(
            [
                ['id' => 1, 'name' => 'one'],
                ['id' => 2, 'name' => 'two'],
                ['id' => 3, 'name' => 'three'],
            ],
            $resultSet->toArray()
        );

        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $resultSet->initialize([1,2,3]); //Not castable items
        $resultSet->toArray();
    }


    public function testCountArray()
    {
        $resultSet = new GenericResultSet();

        $this->assertEquals(1, $resultSet->count());
    }

    /**
     * @expectedException \Matryoshka\Model\Exception\RuntimeException
     */
    public function testCountException()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\AbstractResultSet');

        $this->assertEquals(0, $resultSet->count());
    }

}
