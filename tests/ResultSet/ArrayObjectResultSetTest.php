<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet;

/**
 * Class ArrayObjectResultSetTest
 */
class ArrayObjectResultSetTest extends \PHPUnit_Framework_TestCase
{

    public function testCurrent()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\ArrayObjectResultSet');
        $resultSet->initialize(new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
        ]));
        $this->assertEquals(new \ArrayObject(['id' => 1, 'name' => 'one']), $resultSet->current());
        $resultSet->next();
        $this->assertNull($resultSet->current());
    }

    public function testSetObjectPrototypeShouldThrowExceptionWhenInvalidType()
    {
        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\ArrayObjectResultSet');
        $resultSet->setObjectPrototype(new \stdClass);
    }

    public function testGetSetObjectPrototype()
    {
        $prototype = new \ArrayObject([]);
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\ArrayObjectResultSet');
        $this->assertSame($resultSet, $resultSet->setObjectPrototype($prototype));
        $this->assertSame($prototype, $resultSet->getObjectPrototype());
    }
}
