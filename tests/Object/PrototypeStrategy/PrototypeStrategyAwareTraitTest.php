<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object\PrototypeStrategy;

use Matryoshka\Model\Object\IdentityAwareInterface;

/**
 * Class PrototypeStrategyAwareTraitTest
 */
class PrototypeStrategyAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /** @var PrototypeStrategyAwareInterface */
    protected $traitObject;

    protected $mockStrategy;

    public function setUp()
    {
        $this->traitObject = $this->getObjectForTrait('Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyAwareTrait');
        $this->mockStrategy = $this->getMockForAbstractClass('Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyInterface');
    }

    public function testSetPrototypeStrategy()
    {
        $this->assertSame($this->traitObject, $this->traitObject->setPrototypeStrategy($this->mockStrategy));
        $this->assertAttributeSame($this->mockStrategy, 'prototypeStrategy', $this->traitObject);
    }

    public function testGetPrototypeStrategy()
    {
        // Test default
        $this->assertInstanceOf(
            'Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyInterface',
            $this->traitObject->getPrototypeStrategy()
        );
        $this->traitObject->setPrototypeStrategy($this->mockStrategy);
        $this->assertEquals($this->mockStrategy, $this->traitObject->getPrototypeStrategy());
    }
}
