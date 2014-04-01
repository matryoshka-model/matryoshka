<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Model;
use MatryoshkaTest\Model\Mock\MockDataGataway;
use MatryoshkaTest\Model\Mock\MockModel;
use Matryoshka\Model\ResultSet\ResultSet;

use MatryoshkaTest\Model\Mock\ResultSet\MockResultsetHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
use MatryoshkaTest\Model\TestAsset\ConcreteAbstractModel;


class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Matryoshka\Model\Model
     */
    protected $model;

    protected $mockDataGateway;

    protected $mockCriteria;

    protected $resultSet;

    public function setUp()
    {
        $this->mockDataGateway = $this->getMock('stdClass');

        $this->resultSet = new ResultSet();

        $this->model = new Model($this->mockDataGateway, $this->resultSet);
    }

    public function testWithoutConstructor()
    {
        $model = new ConcreteAbstractModel();
        $this->assertNull($model->getObjectPrototype());
        $this->assertNull($model->getResultSetPrototype());
        $this->assertNull($model->getHydrator());
        $this->assertNull($model->getInputFilter());
        $this->assertNull($model->getDataGateway());
    }

    public function testGetDataGateway()
    {
        $this->assertSame($this->mockDataGateway, $this->model->getDataGateway());
    }

    public function testGetDefaultResultSet(){
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $this->model->getResultSetPrototype());
    }

    public function testGetResultSetPrototype()
    {
        $this->assertSame($this->resultSet, $this->model->getResultSetPrototype());
    }

    public function testGetObjectPrototype()
    {
        $this->assertSame($this->resultSet->getObjectPrototype(), $this->model->getObjectPrototype());
    }

    public function testCreate()
    {
        $prototype = $this->model->getObjectPrototype();
        $newObj    = $this->model->create();

        $this->assertEquals($prototype, $newObj);
        $this->assertNotSame($prototype, $newObj);
    }

    public function testFindAbstractCriteria()
    {
        $criteria = new \MatryoshkaTest\Model\Mock\Criteria\MockCriteria();

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $resurlset,  sprintf("Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface", get_class($resurlset) ) );
    }

    public function testFindClosureCriteria()
    {
        $criteria = new \Matryoshka\Model\Criteria\CallableCriteria('MatryoshkaTest\Model\Mock\Criteria\MockCallable::applyTest');

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $resurlset,  sprintf("Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface", get_class($resurlset) ) );
    }

    public function testConstructHydrator()
    {
        $hydrator = new ClassMethods();
        $model    = new Model(new MockDataGataway(), new MockResultsetHydrator(), $hydrator );

        $this->assertSame($hydrator, $model->getHydrator());
    }
}
