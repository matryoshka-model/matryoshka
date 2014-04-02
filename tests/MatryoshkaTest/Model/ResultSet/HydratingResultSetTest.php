<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet;

use MatryoshkaTest\Model\ResultSet\TestAsset\ItemWithToArray;
class HydratingResultSetTest extends AbstractResultSetTest//\PHPUnit_Framework_TestCase
{

    public function testCurrent()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\HydratingResultSet');
        $resultSet->initialize(new \ArrayIterator(array(
            array('id' => 1, 'name' => 'one'),
        )));
        $this->assertEquals(new \ArrayObject(array('id' => 1, 'name' => 'one')), $resultSet->current());
        $resultSet->next();
        $this->assertNull($resultSet->current());

    }

    public function testSetObjectPrototypeShouldThrowExceptionWhenInvalidType()
    {
        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\HydratingResultSet');
        $resultSet->setObjectPrototype('not an object');
    }

    public function testGetSetObjectPrototype()
    {
        $prototype = new \ArrayObject(array());
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\HydratingResultSet');
        $this->assertSame($resultSet, $resultSet->setObjectPrototype($prototype));
        $this->assertSame($prototype, $resultSet->getObjectPrototype());
    }

    public function testToArray()
    {
        $resultSet = $this->getMockForAbstractClass('\Matryoshka\Model\ResultSet\HydratingResultSet');
        $resultSet->initialize(new \ArrayIterator(array(
            array('id' => 1, 'name' => 'one'),
            array('id' => 2, 'name' => 'two'),
            array('id' => 3, 'name' => 'three'),
        )));
        $this->assertEquals(
            array(
                array('id' => 1, 'name' => 'one'),
                array('id' => 2, 'name' => 'two'),
                array('id' => 3, 'name' => 'three'),
            ),
            $resultSet->toArray()
        );
    }

}
