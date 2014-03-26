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
use Matryoshka\Model\Criteria\Mongo\DefaultCriteria;

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

        $this->model->setDefaultCriteria(new \Matryoshka\Model\Criteria\Mongo\DefaultCriteria());
        $this->assertInstanceOf('\Matryoshka\Model\Criteria\CriteriaInterface', $this->model->getDefaultCriteria());
    }

    public function testGetResultSetPrototype()
    {
        $this->assertSame($this->resultSet, $this->model->getResultSetPrototype());
    }

    public function testFindDataGateway()
    {
        $criteria = new DefaultCriteria();

        $resurlset = $this->model->find($criteria);
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\ResultSetInterface', $resurlset,  sprintf("Class %s not instance of \Matryoshka\Model\ResultSet\ResultSetInterface", get_class($resurlset) ) );
    }
}
