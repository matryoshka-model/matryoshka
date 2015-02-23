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
class ArrayObjectResultSetTest extends AbstractResultSetTest
{

    public function setUp()
    {
        $this->resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\ArrayObjectResultSet');
    }


    public function testCurrent()
    {
        $resultSet = $this->resultSet;
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
        $resultSet = $this->resultSet;
        $resultSet->setObjectPrototype(new \stdClass);
    }

}
