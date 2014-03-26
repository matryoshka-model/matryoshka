<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Model;
use Matryoshka\Model\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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

    public function testGetDataGateway()
    {
        $this->assertSame($this->mockDataGateway, $this->model->getDataGateway());
    }

    public function testGetDefaultCriteria()
    {
        $this->assertSame(null, $this->model->getDefaultCriteria());
    }

    public function testSetDefaultCriteria()
    {
        $returnModel = $this->model->setDefaultCriteria(new \MatryoshkaTest\Model\Criteria\MockCriteria());
        $this->assertSame($this->model, $returnModel);

        $this->assertInstanceOf('\Matryoshka\Model\Criteria\CriteriaInterface', $this->model->getDefaultCriteria());
    }

    public function testGetDefaultResultSet(){
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $this->model->getResultSetPrototype());
    }

    public function testGetResultSetPrototype()
    {
        $this->assertSame($this->resultSet, $this->model->getResultSetPrototype());
    }

    public function testFindAbstractCriteria()
    {
        $criteria = new \MatryoshkaTest\Model\Criteria\MockCriteria();

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $resurlset,  sprintf("Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface", get_class($resurlset) ) );
    }

    public function testFindClosureCriteria()
    {
        $criteria = new \Matryoshka\Model\Criteria\CallableCriteria('MatryoshkaTest\Model\Criteria\MockCallable::applyTest');

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $resurlset,  sprintf("Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface", get_class($resurlset) ) );
    }

    public function testSingleConstruct()
    {
        $model = new Model(new \MatryoshkaTest\Model\MockDataGataway());
    }

    /**
     * @expectedException \Matryoshka\Model\Exception\UnexpectedValueException
     */
    public function testExceptionConstruct()
    {
        $model = new Model($this->mockDataGateway);
    }
}
