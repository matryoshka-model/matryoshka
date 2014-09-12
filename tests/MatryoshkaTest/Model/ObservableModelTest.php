<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\ResultSet\ArrayObjectResultSet as ResultSet;
use Matryoshka\Model\ObservableModel;

/**
 * Class ObservableModelTest
 */
class ObservableModelTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObservableModel */
    protected $model;

    public function setUp()
    {
        $mockDataGateway = $this->getMock('stdClass');
        $resultSet = new ResultSet();
        $this->model = new ObservableModel($mockDataGateway, $resultSet);
    }

    public function testFind()
    {
//        TODO:
        $this->assertEquals(1, 1);
    }
}
 