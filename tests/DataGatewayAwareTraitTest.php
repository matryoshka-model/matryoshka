<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use Matryoshka\Model\DataGatewayAwareTrait;
use MatryoshkaTest\Model\TestAsset\ArrayGateway\ArrayGateway;

/**
 * Class DataGatewayAwareTraitTest
 */
class DataGatewayAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /** @var DataGatewayAwareTrait */
    protected $traitObject;

    protected $dataGateway;

    public function setUp()
    {
        $this->traitObject = $this->getObjectForTrait('Matryoshka\Model\DataGatewayAwareTrait');
        $this->dataGateway = new ArrayGateway();
    }

    public function testSetDataGateway()
    {
        $this->assertSame($this->traitObject, $this->traitObject->setDataGateway($this->dataGateway));
        $this->assertAttributeEquals($this->dataGateway, 'dataGateway', $this->traitObject);
    }

    public function testGetDataGateway()
    {
        $this->traitObject->setDataGateway($this->dataGateway);
        $this->assertEquals($this->dataGateway, $this->traitObject->getDataGateway());
    }
}
