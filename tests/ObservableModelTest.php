<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\ModelEvent;
use Matryoshka\Model\ObservableModel;
use MatryoshkaTest\Model\Mock\Criteria\MockCriteria;
use MatryoshkaTest\Model\TestAsset\ResultSet;

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
        $postEventCallend = false;

        $this->model->getEventManager()->attach('find.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $preEventCalled = true;
                $this->assertSame($this->model, $e->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\ReadableCriteriaInterface', $e->getCriteria());
        });

        $this->model->getEventManager()->attach('find.post', function ($e) use (&$postEventCallend) {
                /** @var $e ModelEvent */
                $postEventCallend = true;
                $this->assertSame($this->model, $e->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\ReadableCriteriaInterface', $e->getCriteria());
        });

        parent::testFind();

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCallend);

        //Test stop propagation
        $preEventCalled = false;
        $postEventCallend = false;

        $listener = $this->model->getEventManager()->attach('find.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $e->stopPropagation();
                $resultSet = clone $e->getTarget()->getResultSetPrototype();
                $resultSet->initialize([]);
                return $resultSet;
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
        $this->assertCount(0, $resultset);
        $this->assertTrue($preEventCalled);
        $this->assertFalse($postEventCallend);


        //Test exception
        $this->model->getEventManager()->detach($listener);
        $this->model->getEventManager()->attach('find.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $e->stopPropagation();
            return 'invalid response';
        });

        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $resultset = $this->model->find($mockCriteria);

    }

    /**
     * @dataProvider saveDataProvider
     */
    public function testSave($data, $expected, $hydrator = null)
    {
        $preEventCalled = false;
        $postEventCallend = false;

        $this->model->getEventManager()->attach('save.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $preEventCalled = true;
                $this->assertSame($this->model, $e->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\WritableCriteriaInterface', $e->getCriteria());
        });

        $this->model->getEventManager()->attach('save.post', function ($e) use (&$postEventCallend) {
                /** @var $e ModelEvent */
                $postEventCallend = true;
                $this->assertSame($this->model, $e->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\WritableCriteriaInterface', $e->getCriteria());
        });

        parent::testSave($data, $expected, $hydrator);

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCallend);

        //Test stop propagation
        $preEventCalled = false;
        $postEventCallend = false;

        $listener = $this->model->getEventManager()->attach('save.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $e->stopPropagation();
                return 1;
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\WritableCriteriaInterface',
            ['applyWrite']
        );

        $this->assertEquals(1, $this->model->save($mockCriteria, $data));
        $this->assertTrue($preEventCalled);
        $this->assertFalse($postEventCallend);

        //Test exception
        $this->model->getEventManager()->detach($listener);
        $this->model->getEventManager()->attach('save.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $e->stopPropagation();
            return 'invalid response';
        });

        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $resultset = $this->model->save($mockCriteria, $data);
    }

    public function testDelete()
    {
        $preEventCalled = false;
        $postEventCallend = false;

        $this->model->getEventManager()->attach('delete.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $preEventCalled = true;
                $this->assertSame($this->model, $e->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\DeletableCriteriaInterface', $e->getCriteria());
        });

        $this->model->getEventManager()->attach('delete.post', function ($e) use (&$postEventCallend) {
                /** @var $e ModelEvent */
                $postEventCallend = true;
                $this->assertSame($this->model, $e->getTarget());
                $this->assertInstanceOf('\Matryoshka\Model\Criteria\DeletableCriteriaInterface', $e->getCriteria());
        });

        parent::testDelete();

        $this->assertTrue($preEventCalled);
        $this->assertTrue($postEventCallend);

        //Test stop propagation
        $preEventCalled = false;
        $postEventCallend = false;

        $listener = $this->model->getEventManager()->attach('delete.pre', function ($e) use (&$preEventCalled) {
                /** @var $e ModelEvent */
                $e->stopPropagation();
                return 1;
        });

        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\DeletableCriteriaInterface',
            ['applyDelete']
        );

        $this->assertEquals(1, $this->model->delete($mockCriteria));
        $this->assertTrue($preEventCalled);
        $this->assertFalse($postEventCallend);

        //Test exception
        $this->model->getEventManager()->detach($listener);
        $this->model->getEventManager()->attach('delete.pre', function ($e) use (&$preEventCalled) {
            /** @var $e ModelEvent */
            $e->stopPropagation();
            return 'invalid response';
        });

        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $resultset = $this->model->delete($mockCriteria);
    }
}
