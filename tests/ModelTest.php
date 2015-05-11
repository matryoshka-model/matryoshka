<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Model;
use MatryoshkaTest\Model\TestAsset\ResultSet;

/**
 * Class ModelTest
 */
class ModelTest extends AbstractModelTest
{
    /**
     * @var \Matryoshka\Model\Model
     */
    protected $model;

    public function setUp()
    {
        $this->mockDataGateway = $this->getMock('stdClass');
        $this->resultSet = new ResultSet();
        $this->model = new Model($this->mockDataGateway, $this->resultSet);
    }

    public function testConstructorDefaults()
    {
        $this->assertSame($this->resultSet, $this->model->getResultSetPrototype());
        $this->assertSame($this->mockDataGateway, $this->model->getDataGateway());
    }
}
