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
use MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria;
use MatryoshkaTest\Model\ResultSet\TestAsset\GenericResultSet;
use MatryoshkaTest\Model\TestAsset\ConcreteAbstractModel;

/**
 * Class ModelEventTest
 */
class ModelEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var ModelEvent */
    protected $event;

    public function setUp()
    {
        $this->event = new ModelEvent();
    }

    public function testSetTarget()
    {
        $model = new ConcreteAbstractModel();
        $this->event->setTarget($model);
        $this->assertSame($model, $this->event->getTarget());

        $wrong = new \stdClass();
        $this->setExpectedException('\Matryoshka\Model\Exception\InvalidArgumentException');
        $this->event->setTarget($wrong);
    }

    public function testCriteriaIsNullByDefault()
    {
        $this->assertNull($this->event->getCriteria());
    }

    public function testResultSetNullByDefault()
    {
        $this->assertNull($this->event->getResultSet());
    }

    public function testCriteriaIsMutable()
    {
        $criteria = new ConcreteCriteria();
        $this->event->setCriteria($criteria);
        $this->assertSame($criteria, $this->event->getCriteria());
    }

    public function testResultSetIsMutable()
    {
        $resultSet = new GenericResultSet();
        $this->event->setResultSet($resultSet);
        $this->assertSame($resultSet, $this->event->getResultSet());
    }

    public function testCriteriaIsMutableViaSetParam()
    {
        $criteria = new ConcreteCriteria();
        $this->event->setParam('criteria', $criteria);
        $this->assertSame($criteria, $this->event->getCriteria());
        $this->assertSame($criteria, $this->event->getParam('criteria'));
    }

    public function testResultSetIsMutableViaSetParam()
    {
        $resultSet = new GenericResultSet();
        $this->event->setParam('resultSet', $resultSet);
        $this->assertSame($resultSet, $this->event->getResultSet());
        $this->assertSame($resultSet, $this->event->getParam('resultSet'));
    }

    public function testParamIsMutableViaParam()
    {
        $this->event->setParam('key', 'value');
        $this->assertSame('value', $this->event->getParam('key'));
    }

    public function testSpecializedParametersMayBeSetViaSetParams()
    {
        $criteria = new ConcreteCriteria();
        $resultSet = new GenericResultSet();

        $params = [
            'criteria'  => $criteria,
            'data'      => [],
            'result'    => 1,
            'resultSet' => $resultSet,
            'key'       => 'value',
        ];

        $this->event->setParams($params);
        $this->assertEquals($params, $this->event->getParams());

        $this->assertSame($params['criteria'], $this->event->getCriteria());
        $this->assertSame($params['criteria'], $this->event->getParam('criteria'));

        $this->assertSame($params['data'], $this->event->getData());
        $this->assertSame($params['data'], $this->event->getParam('data'));

        $this->assertSame($params['result'], $this->event->getResult());
        $this->assertSame($params['result'], $this->event->getParam('result'));

        $this->assertSame($params['resultSet'], $this->event->getResultSet());
        $this->assertSame($params['resultSet'], $this->event->getParam('resultSet'));

        $this->assertEquals($params['key'], $this->event->getParam('key'));
    }

    public function testParameterPassedThroughObject()
    {
        $obj = new \stdClass();
        $obj->foo = 'bar';
        $obj->criteria = new ConcreteCriteria();
        $obj->resultSet = new GenericResultSet();
        $this->event->setParams($obj);

        $this->assertObjectHasAttribute('foo', $this->event->getParams());

        $this->assertObjectHasAttribute('criteria', $this->event->getParams());
        $this->assertSame($this->event->getCriteria(), $this->event->getParams()->criteria);
        $this->assertSame($obj->criteria, $this->event->getParams()->criteria);

        $this->assertObjectHasAttribute('resultSet', $this->event->getParams());
        $this->assertSame($this->event->getResultSet(), $this->event->getParams()->resultSet);
        $this->assertSame($obj->resultSet, $this->event->getParams()->resultSet);

        $this->assertObjectHasAttribute('data', $this->event->getParams());
        $this->assertNull($this->event->getParams()->data);

        $this->assertObjectHasAttribute('result', $this->event->getParams());
        $this->assertNull($this->event->getParams()->result);
    }
}
