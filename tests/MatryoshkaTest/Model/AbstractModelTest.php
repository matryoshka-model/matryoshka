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
class AbstractModelTest extends \PHPUnit_Framework_TestCase
{

    protected $model;

    protected $mockDataGateway;

    protected $mockCriteria;

    protected $resultSet;

    public function setUp()
    {
        $this->mockDataGateway = $this->getMock('stdClass');

        $this->mockCriteria = $this->getMock('Matryoshka\Model\Criteria\CriteriaInterface');

        $this->resultSet = new ResultSet();

        $this->model = new Model($this->mockDataGateway, $this->mockCriteria, $this->resultSet);
    }

    public function testGetDataGateway()
    {
        $this->assertSame($this->mockDataGateway, $this->model->getDataGateway());
    }

    public function testGetDefaultCriteria()
    {
        $this->assertSame($this->mockCriteria, $this->model->getDefaultCriteria());
    }

    public function testGetResultSetPrototype()
    {
        $this->assertSame($this->resultSet, $this->model->getResultSetPrototype());
    }
    
    public function testProcessCriteria()
    {
        
    }



}
