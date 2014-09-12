<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Criteria\CallableCriteria;
use Matryoshka\Model\Model;
use Matryoshka\Model\ObservableModel;
use MatryoshkaTest\Model\Mock\Criteria\MockCriteria;
use Matryoshka\Model\ResultSet\ArrayObjectResultSet as ResultSet;
use MatryoshkaTest\Model\TestAsset\ConcreteAbstractModel;
use MatryoshkaTest\Model\TestAsset\HydratorObject;
use MatryoshkaTest\Model\TestAsset\ToArrayObject;
use Zend\Stdlib\Hydrator\ArraySerializable;
use MatryoshkaTest\Model\TestAsset\HydratorAwareObject;
use MatryoshkaTest\Model\TestAsset\InputFilterAwareObject;

/**
 * Class AbstractModelTest
 */
class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Matryoshka\Model\Model
     */
    protected $model;

    /**
     * @var \Matryoshka\Model\ObservableModel
     */
    protected $obsModel; // TODO: test ObservableModel

    protected $mockDataGateway;

    protected $mockCriteria;

    protected $resultSet;

    public function setUp()
    {
        $this->mockDataGateway = $this->getMock('stdClass');

        $this->resultSet = new ResultSet();

        $this->model = new Model($this->mockDataGateway, $this->resultSet);

        $this->obsModel = new ObservableModel($this->mockDataGateway, $this->resultSet);
    }

    public function testWithoutConstructor()
    {
        $model = new ConcreteAbstractModel();
        $this->assertNull($model->getResultSetPrototype());
        $this->assertNull($model->getDataGateway());
    }

    public function testShouldThrowExceptionWhenNoObjectPrototype()
    {
        $model = new ConcreteAbstractModel();
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $model->getObjectPrototype();
    }

    public function testGetHydratorShouldThrowExceptionWhenNoHydratorAndNoObjectPrototype()
    {
        $model = new ConcreteAbstractModel();
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $model->getHydrator();
    }

    public function testShouldThrowExceptionWhenNoInputFilterAndNoObjectPrototype()
    {
        $model = new ConcreteAbstractModel();
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $model->getInputFilter();
    }

    public function testShouldThrowExceptionWhenNoInputFilter()
    {
        $model = new ConcreteAbstractModel();
        $model->setResultSetPrototype(new ResultSet());
        $model->getResultSetPrototype()->setObjectPrototype(new \ArrayObject);

        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');

        $model->getInputFilter();
    }

    public function testGetSetHydrator()
    {
        $model = clone $this->model;

        //Assume no hydrator set (hydrator can be null)
        $this->assertNull($model->getHydrator());

        $hydratableObject = new HydratorAwareObject();
        $model->getResultSetPrototype()->setObjectPrototype($hydratableObject);
        $this->assertSame($hydratableObject->getHydrator(), $model->getHydrator());

        $this->assertInstanceOf('\Matryoshka\Model\Model', $model->setHydrator(new ArraySerializable()));
        $this->assertInstanceOf('\Zend\Stdlib\Hydrator\HydratorInterface', $model->getHydrator());
    }

    public function testGetSetInputFilter()
    {
        $model = clone $this->model;

        $filterableObject = new InputFilterAwareObject();
        $model->getResultSetPrototype()->setObjectPrototype($filterableObject);
        $this->assertSame($filterableObject->getInputFilter(), $model->getInputFilter());

        $this->assertInstanceOf('\Matryoshka\Model\Model', $model->setInputFilter(new \Zend\InputFilter\InputFilter()));
        $this->assertInstanceOf('\Zend\InputFilter\InputFilterInterface', $model->getInputFilter());
    }

    public function testGetDataGateway()
    {
        $this->assertSame($this->mockDataGateway, $this->model->getDataGateway());
    }

    public function testGetDefaultResultSet()
    {
        $this->assertInstanceOf(
            '\Matryoshka\Model\ResultSet\ResultSetInterface',
            $this->model->getResultSetPrototype()
        );
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
        $newObj = $this->model->create();

        $this->assertEquals($prototype, $newObj);
        $this->assertNotSame($prototype, $newObj);
    }

    public function testCreateObservable()
    {
        $prototype = $this->obsModel->getObjectPrototype();
        $newObj = $this->obsModel->create();

        $this->assertEquals($prototype, $newObj);
        $this->assertNotSame($prototype, $newObj);
    }

    public function testFindAbstractCriteria()
    {
        $criteria = new MockCriteria();

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf(
            '\Matryoshka\Model\ResultSet\ResultSetInterface',
            $resurlset,
            sprintf(
                'Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface',
                get_class($resurlset)
            )
        );
    }

    public function testFindClosureCriteria()
    {
        $criteria = new CallableCriteria(
            'MatryoshkaTest\Model\Mock\Criteria\MockCallable::applyTest'
        );

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf(
            '\Matryoshka\Model\ResultSet\ResultSetInterface',
            $resurlset,
            sprintf(
                'Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface',
                get_class($resurlset)
            )
        );
    }

    /**
     * @dataProvider saveDataProvider
     */
    public function testSave($data, $expected, $hydrator = null)
    {
        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\WritableCriteriaInterface',
            ['applyWrite']
        );

        if ($hydrator) {
            $this->model->setHydrator($hydrator);
        }

        $mockCriteria->expects($this->at(0))->method('applyWrite')->with(
            $this->equalTo($this->model),
            $this->equalTo($expected)
        )->will($this->returnValue(1));

        $this->assertEquals(1, $this->model->save($mockCriteria, $data));
    }


    /**
     * @dataProvider saveExceptionDataProvider
     * @expectedException \Matryoshka\Model\Exception\RuntimeException
     */
    public function testSaveRuntimeException($data, $hydrator = null)
    {
        $mockCriteria = $this->getMock('\Matryoshka\Model\Criteria\WritableCriteriaInterface');

        if ($hydrator) {
            $this->model->setHydrator($hydrator);
        }

        $this->model->save($mockCriteria, $data);
    }


    public function testDelete()
    {
        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\DeletableCriteriaInterface',
            ['applyDelete']
        );
        $mockCriteria->expects($this->at(0))->method('applyDelete')->with(
            $this->equalTo($this->model)
        )->will($this->returnValue(1));

        $this->assertEquals(1, $this->model->delete($mockCriteria));
    }


    /**
     * Save Data Provider
     *
     * @return array
     */
    public function saveDataProvider()
    {
        return [
            [['foo' => 'bar'], ['foo' => 'bar']],
            [['foo' => 'bar'], ['foo' => 'bar'], new ArraySerializable()],
            [new ToArrayObject(['foo' => 'bar']), ['foo' => 'bar']],
            [new \ArrayObject(['foo' => 'bar']), ['foo' => 'bar']],
            [new HydratorAwareObject(['foo' => 'bar']), ['foo' => 'bar']],
            [new \ArrayObject(['foo' => 'bar']), ['foo' => 'bar'], new ArraySerializable()],
        ];
    }


    /**
     * Save Exception Data Provider
     *
     * @return array
     */
    public function saveExceptionDataProvider()
    {
        return [
            ['Yak'],
            [['Yak'], new HydratorObject()]
        ];
    }

    public function testGetSetPaginatorCriteria()
    {
        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\PaginableCriteriaInterface',
            ['getPaginatorAdapter']
        );

        $this->assertInstanceOf('\Matryoshka\Model\Model', $this->model->setPaginatorCriteria($mockCriteria));
        $this->assertSame($mockCriteria, $this->model->getPaginatorCriteria());
    }

    public function testGetPaginatorCriteriaShouldThrowExceptionWhenNoCriteria()
    {
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->model->getPaginatorCriteria();
    }

    public function testGetPaginatorAdapter()
    {
        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\PaginableCriteriaInterface',
            ['getPaginatorAdapter']
        );

        $mockPaginatorAdapter = $this->getMock(
            '\Zend\Paginator\Adapter\AdapterInterface'
        );

        $mockCriteria->expects($this->at(0))->method('getPaginatorAdapter')->with(
            $this->equalTo($this->model)
        )->will($this->returnValue($mockPaginatorAdapter));

        //Test passing criteria
        $this->assertSame($mockPaginatorAdapter, $this->model->getPaginatorAdapter($mockCriteria));

        //Test with default criteria
        $mockCriteria->expects($this->at(0))->method('getPaginatorAdapter')->with(
            $this->equalTo($this->model)
        )->will($this->returnValue($mockPaginatorAdapter));

        $this->model->setPaginatorCriteria($mockCriteria);
        $this->assertSame($mockPaginatorAdapter, $this->model->getPaginatorAdapter());
    }

}
