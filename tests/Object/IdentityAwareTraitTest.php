<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object;

use Matryoshka\Model\Object\IdentityAwareInterface;

/**
 * Class IdentityAwareTraitTest
 */
class IdentityAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /** @var IdentityAwareInterface */
    protected $traitObject;


    public function setUp()
    {
        $this->traitObject = $this->getObjectForTrait('Matryoshka\Model\Object\IdentityAwareTrait');
    }

    public function testSetModel()
    {
        $this->assertSame($this->traitObject, $this->traitObject->setId('foo'));
        $this->assertAttributeEquals('foo', 'id', $this->traitObject);
    }

    public function testGetModel()
    {
        $this->assertNull($this->traitObject->getId());
        $this->traitObject->setId('bar');
        $this->assertEquals('bar', $this->traitObject->getId());
    }
}
