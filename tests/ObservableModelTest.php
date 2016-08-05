<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\ModelEvent;
use Matryoshka\Model\ObservableModel;
use MatryoshkaTest\Model\Mock\Criteria\MockCriteria;
use MatryoshkaTest\Model\TestAsset\ResultSet;
use Zend\EventManager\Event;

/**
 * Class ObservableModelTest
 */
class ObservableModelTest extends ModelTest
{
    /**
     * @var ObservableModel
     */
    protected $model;

    public function setUp()
    {
        $this->mockDataGateway = $this->getMock('stdClass');
        $this->resultSet = new ResultSet();
        $this->model = new ObservableModel($this->mockDataGateway, $this->resultSet);
    }

    public function testFind()
    {
        $preEventCalled = false;
        $postEventCalled = false;

        $this->model->getEventManager()->attach('find.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $preEventCalled = true;
            $this->assertSame($this->model, $e->getTarget()->getTarget());
            $this->assertInstanceOf('\Matryoshka\Model\Criteria\ReadableCriteriaInterface', $e->getTarget()->getCriteria());
        });

        $this->model->getEventManager()->attach('find.post', function ($e) use (&$postEventCalled) {
            /** @var $e ModelEvent */
            $postEventCalled = true;
            $this->assertSame($this->model, $e->getTarget()->getTarget());
            $this->assertInstanceOf('\Matryoshka\Model\Criteria\ReadableCriteriaInterface', $e->getTarget()->getCriteria());
        });

        parent::testFind();

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCalled);


        //Test invalid result
        $preEventCalled = false;
        $postEventCalled = false;
        $this->model->getEventManager()->clearListeners('find.pre');
        $this->model->getEventManager()->clearListeners('find.post');
        $this->model->getEventManager()->attach('find.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $preEventCalled = true;
            $e->stopPropagation();
            return 'invalid result';
        });

        $this->model->getEventManager()->attach('find.post', function ($e) use (&$postEventCalled) {
            /** @var $e ModelEvent */
            $postEventCalled = true;
            $e->stopPropagation();
            return 'invalid result';
        });

        $mockCriteria = new MockCriteria();
        $resultset = $this->model->find($mockCriteria);
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $resultset);

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCalled);
    }


    public function testFindStopPropagation()
    {
        //Test stop propagation (pre-stage)
        $preEventCalled = false;
        $postEventCallend = false;

        $emptyResultSet = clone $this->model->getResultSetPrototype();

        $listener = $this->model->getEventManager()->attach('find.pre', function ($e) use (&$preEventCalled, $emptyResultSet) {
            /** @var $e Event */

            $preEventCalled = true;
            $e->stopPropagation($preEventCalled);
            return $emptyResultSet;
        });

        $mockCriteria = new MockCriteria();

        $resultset = $this->model->find($mockCriteria);
        $this->assertInstanceOf(
            '\Matryoshka\Model\ResultSet\ResultSetInterface',
            $resultset,
            sprintf(
                'Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface',
                get_class($resultset)
            )
        );

        $this->assertSame($emptyResultSet, $resultset);

        $this->assertTrue($preEventCalled);
        $this->assertFalse($postEventCallend);

        //Test stop propagation (post-stage)
        $preEventCalled = false;
        $postEventCallend = false;

        $this->model->getEventManager()->detach($listener);
        $listener = $this->model->getEventManager()->attach('find.post', function ($e) use (&$postEventCallend, $emptyResultSet) {
            /** @var $e ModelEvent */
            $postEventCallend = true;
            $e->stopPropagation($postEventCallend);
            return $emptyResultSet;
        });

        $mockCriteria = new MockCriteria();

        $resultset = $this->model->find($mockCriteria);
        $this->assertInstanceOf(
            '\Matryoshka\Model\ResultSet\ResultSetInterface',
            $resultset,
            sprintf(
                'Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface',
                get_class($resultset)
            )
        );

        $this->assertSame($emptyResultSet, $resultset);
        $this->assertFalse($preEventCalled);
        $this->assertTrue($postEventCallend);

    }


    /**
     * @dataProvider saveDataProvider
     */
    public function testSave($data, $expected, $hydrator = null, $objectPrototype = null)
    {
        $preEventCalled = false;
        $postEventCalled = false;

        $this->model->getEventManager()->attach('save.pre', function ($e) use (&$preEventCalled, $data) {
            /** @var $e Event */
            $preEventCalled = true;
            $this->assertSame($this->model, $e->getTarget()->getTarget());
            $this->assertInstanceOf('\Matryoshka\Model\Criteria\WritableCriteriaInterface', $e->getTarget()->getCriteria());
            $this->assertSame($data, $e->getTarget()->getData());
            $this->assertNull($e->getTarget()->getResult());
            $this->assertNull($e->getTarget()->getResultSet());

        });

        $this->model->getEventManager()->attach('save.post', function ($e) use (&$postEventCalled, $data, $expected) {
            /* @var $e Event */
            $postEventCalled = true;
            $this->assertSame($this->model, $e->getTarget()->getTarget());
            $this->assertInstanceOf('\Matryoshka\Model\Criteria\WritableCriteriaInterface', $e->getTarget()->getCriteria());
            $this->assertSame($data, $e->getTarget()->getData());
            $this->assertNull($e->getTarget()->getResultSet());

            if (is_int($e->getTarget()->getResult())) {
                $this->assertSame(1, $e->getTarget()->getResult());
            } else {
                $this->assertNull($e->getTarget()->getResult());
            }
        });

        parent::testSave($data, $expected, $hydrator, $objectPrototype);

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCalled);


        //Test invalid result
        $preEventCalled = false;
        $postEventCalled = false;
        $this->model->getEventManager()->clearListeners('save.pre');
        $this->model->getEventManager()->clearListeners('save.post');
        $this->model->getEventManager()->attach('save.pre', function ($e) use (&$preEventCalled) {
            /* @var $e Event */
            $preEventCalled = true;
            $e->stopPropagation();
            return 'invalid result';
        });

        $this->model->getEventManager()->attach('save.post', function ($e) use (&$postEventCalled) {
            /* @var $e Event */
            $postEventCalled = true;
            $e->stopPropagation();
            return 'invalid result';
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\WritableCriteriaInterface',
            ['applyWrite']
        );
        $result = $this->model->save($mockCriteria, $data);
        $this->assertNull($result);
        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCalled);
    }

    public function testSaveStopPropagation()
    {
        $data = ['foo' => 'bar'];

        //Test stop propagation (pre-stage)
        $preEventCalled = false;
        $postEventCalled = false;

        $listener = $this->model->getEventManager()->attach('save.pre', function ($e) use (&$preEventCalled) {
            /** @var $e Event */
            $preEventCalled = true;
            $e->stopPropagation($preEventCalled);
            return 10;
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\WritableCriteriaInterface',
            ['applyWrite']
        );

        $this->assertEquals(10, $this->model->save($mockCriteria, $data));
        $this->assertTrue($preEventCalled);
        $this->assertFalse($postEventCalled);


        //Test stop propagation (pre-stage)
        $preEventCalled = false;
        $postEventCalled = false;
        $this->model->getEventManager()->clearListeners('save.pre');
        $listener = $this->model->getEventManager()->attach('save.post', function ($e) use (&$postEventCalled) {
            /** @var $e ModelEvent */
            $postEventCalled = true;
            $e->stopPropagation($postEventCalled);
            return 20;
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\WritableCriteriaInterface',
            ['applyWrite']
        );

        $this->assertEquals(20, $this->model->save($mockCriteria, $data));
        $this->assertFalse($preEventCalled);
        $this->assertTrue($postEventCalled);
    }


    public function testDelete()
    {
        $preEventCalled = false;
        $postEventCalled = false;

        $this->model->getEventManager()->attach('delete.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $preEventCalled = true;
                    $this->assertSame($this->model, $e->getTarget()->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\DeletableCriteriaInterface', $e->getTarget()->getCriteria());
                $this->assertNull($e->getTarget()->getData());
                $this->assertNull($e->getTarget()->getResult());
                $this->assertNull($e->getTarget()->getResultSet());
        });

        $this->model->getEventManager()->attach('delete.post', function ($e) use (&$postEventCalled) {
                /** @var $e ModelEvent */
                $postEventCalled = true;
                $this->assertSame($this->model, $e->getTarget()->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\DeletableCriteriaInterface', $e->getTarget()->getCriteria());
                $this->assertNull($e->getTarget()->getData());
                $this->assertNull($e->getTarget()->getResultSet());

                if (is_int($e->getTarget()->getResult())) {
                    $this->assertSame(1, $e->getTarget()->getResult());
                } else {
                    $this->assertNull($e->getTarget()->getResult());
                }
        });

        parent::testDelete();

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCalled);


        //Test invalid result
        $this->model->getEventManager()->clearListeners('delete.pre');
        $this->model->getEventManager()->clearListeners('delete.post');
        $this->model->getEventManager()->attach('delete.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $e->stopPropagation();
            return 'invalid result';
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\DeletableCriteriaInterface',
            ['applyDelete']
        );
        $result = $this->model->delete($mockCriteria);
        $this->assertNull($result);
    }


    public function testDeleteStopPropagation()
    {

        //Test stop propagation (pre-stage)
        $preEventCalled = false;
        $postEventCalled = false;

        $listener = $this->model->getEventManager()->attach('delete.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $preEventCalled = true;
            $e->stopPropagation($preEventCalled);
            return 1;
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\DeletableCriteriaInterface',
            ['applyDelete']
        );

        $this->assertEquals(1, $this->model->delete($mockCriteria));
        $this->assertTrue($preEventCalled);
        $this->assertFalse($postEventCalled);


        //Test stop propagation (pre-stage)
        $preEventCalled = false;
        $postEventCalled = false;
        $this->model->getEventManager()->clearListeners('delete.pre');
        $listener = $this->model->getEventManager()->attach('delete.post', function ($e) use (&$postEventCalled) {
            /** @var $e Event */
            $postEventCalled = true;
            $e->stopPropagation($postEventCalled);
            return 2;
        });


        $this->assertEquals(2, $this->model->delete($mockCriteria));
        $this->assertFalse($preEventCalled);
        $this->assertTrue($postEventCalled);
    }
}
