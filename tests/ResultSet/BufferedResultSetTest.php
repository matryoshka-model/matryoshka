<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet;

use Matryoshka\Model\ResultSet\BufferedResultSet;
use MatryoshkaTest\Model\ResultSet\TestAsset\GenericResultSet;
use Matryoshka\Model\Exception\RuntimeException;

/**
 * Class BufferedResultSetTest
 *
 * @property \Matryoshka\Model\ResultSet\BufferedResultSet $resultSet
 *
 */
class BufferedResultSetTest extends AbstractResultSetTest
{

    protected $aggregatedResultSet;

    public function setUp()
    {
        $this->aggregatedResultSet = new GenericResultSet();
        $this->resultSet = new BufferedResultSet($this->aggregatedResultSet);
    }


    public function test__constructor()
    {
        $genericResultSet = new GenericResultSet();

        $resultSet = new BufferedResultSet($genericResultSet);
        $this->assertSame($genericResultSet, $resultSet->getResultSet());
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\BufferedResultSetInterface', $resultSet);


        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        new BufferedResultSet(new BufferedResultSet($genericResultSet));
    }

    public function testCountArray()
    {
        $resultSet = $this->resultSet->getResultSet();

        $refl = new \ReflectionClass($resultSet);
        $reflProperty = $refl->getProperty('dataSource');
        $reflProperty->setAccessible(true);
        $reflProperty->setValue($resultSet, ['one', 'two']);

        $this->assertEquals(2, $resultSet->count());
        $this->assertEquals(2, $this->resultSet->count());
    }

    public function testCurrentCallsAggregateCurrentOnce()
    {
        $result = $this->getMock('Iterator');
        $this->resultSet->initialize($result);
        $result->expects($this->once())->method('current')->will($this->returnValue(array('foo' => 'bar')));
        $value1 = $this->resultSet->current();
        $value2 = $this->resultSet->current();
        $this->resultSet->current();
        $this->assertEquals($value1, $value2);
    }

    public function testValidDoesNotCallAggregateValidWhenItemBuffered()
    {
        $result = $this->getMock('Iterator');
        $this->resultSet->initialize($result);
        $result->expects($this->once())->method('current')->will($this->returnValue(array('foo' => 'bar')));
        $result->expects($this->once())->method('valid')->will($this->returnValue(true));
        $value1 = $this->resultSet->valid();
        $this->resultSet->current(); // item buffered
        $value2 = $this->resultSet->valid();
        $this->resultSet->valid();
        $this->assertEquals($value1, $value2);
    }

    public function testEnsurePosition()
    {
        $resultSet = $this->resultSet;
        $aggregated = $this->aggregatedResultSet;

        $dataSource = new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]);

        $resultSet->initialize($dataSource);

        // First iteration (partial)
        foreach ($resultSet as $item) {
            break;
        }

        // First iteration (full)
        foreach ($resultSet as $item);

        // Second iteration (full)
        foreach ($resultSet as $item);

        try {
            $aggregated->next();
            foreach ($resultSet as $item);
            $this->fail(
                'Failed asserting that exception of type "\Matryoshka\Model\Exception\RuntimeException" is thrown, '
                . 'when next() was called on aggregated resultset'
            );
        } catch (RuntimeException $e) {
        }

        try {
            $aggregated->rewind();
            foreach ($resultSet as $item);
            $this->fail(
                'Failed asserting that exception of type "\Matryoshka\Model\Exception\RuntimeException" is thrown, '
                . 'when rewind() was called on aggregated resultset'
            );
        } catch (RuntimeException $e) {
        }
    }


    public function testBuffering()
    {
        $resultSet = $this->resultSet;
        $aggregated = $this->aggregatedResultSet;

        $dataSource = new \ArrayIterator([
            ['id' => 1, 'name' => 'one'],
            ['id' => 2, 'name' => 'two'],
            ['id' => 3, 'name' => 'three'],
        ]);

        $resultSet->initialize($dataSource);
        $this->assertSame($dataSource, $aggregated->getDataSource());


        // First iteration
        $resultSet->rewind();
        $this->assertEquals(0, $resultSet->key());
        $this->assertEquals(0, $aggregated->key());



        $this->assertEquals(['id' => 1, 'name' => 'one'], $resultSet->current());
    }

    public function test__clone()
    {
        $refl = new \ReflectionClass($this->resultSet);
        $reflProperty = $refl->getProperty('resultSet');
        $reflProperty->setAccessible(true);

        $cloned = clone $this->resultSet;

        $this->assertEquals(
            $reflProperty->getValue($this->resultSet),
            $reflProperty->getValue($cloned)
        );

        $this->assertNotSame(
            $reflProperty->getValue($this->resultSet),
            $reflProperty->getValue($cloned)
        );
    }
}
