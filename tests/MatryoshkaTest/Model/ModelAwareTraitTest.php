<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

/**
 * Class ModelAwareTraitTest
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class ModelAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $traitObject;

    protected $modelMock;

    public function setUp()
    {
        $this->traitObject = $this->getObjectForTrait('Matryoshka\Model\ModelAwareTrait');
        $this->modelMock = $this->getMockBuilder('\Matryoshka\Model\Model')
            ->disableOriginalConstructor()
            ->getMock();

    }

    public function testSetModel()
    {
        $this->traitObject->setModel($this->modelMock);
        $this->assertAttributeEquals($this->modelMock, 'model', $this->traitObject);
    }

    public function testGetModel()
    {
        $this->traitObject->setModel($this->modelMock);

        $this->assertEquals($this->modelMock, $this->traitObject->getModel());
    }
}
