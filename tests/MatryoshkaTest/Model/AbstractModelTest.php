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
use Zend\Stdlib\Hydrator\ArraySerializable;
use MatryoshkaTest\Model\TestAsset\HydratorAwareObject;
use Zend\Form\Annotation\InputFilter;
use MatryoshkaTest\Model\TestAsset\InputFilterAwareObject;


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

    public function testSave()
    {
        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\WritableCriteriaInterface',
            array('applyWrite')
        );

        $data = array(
            'foo' => 'bar'
        );

        //Test simple array
        $mockCriteria->expects($this->at(0))->method('applyWrite')->with(
            $this->equalTo($this->model),
            $this->equalTo($data)
        )->will($this->returnValue(true));

        $this->assertTrue($this->model->save($mockCriteria, $data));

        //Test with ArrayObject
        $mockCriteria->expects($this->at(0))->method('applyWrite')->with(
            $this->equalTo($this->model),
            $this->equalTo($data)
        )->will($this->returnValue(true));

        $this->assertTrue($this->model->save($mockCriteria, new \ArrayObject($data)));

        //Test hydratable object
        $hydratableObject = new HydratorAwareObject($data);
        $mockCriteria->expects($this->at(0))->method('applyWrite')->with(
            $this->equalTo($this->model),
            $data
        )->will($this->returnValue(true));

        $this->assertTrue($this->model->save($mockCriteria, $hydratableObject));
    }

    public function testDelete()
    {
        $mockCriteria = $this->getMock(
            '\Matryoshka\Model\Criteria\DeletableCriteriaInterface',
            array('applyDelete')
        );
        $mockCriteria->expects($this->at(0))->method('applyDelete')->with(
            $this->equalTo($this->model)
        )->will($this->returnValue(true));

        $this->assertTrue($this->model->delete($mockCriteria));
    }
}
