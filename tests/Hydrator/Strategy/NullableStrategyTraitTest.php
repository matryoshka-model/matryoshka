<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Hydrator\Strategy;


class NullableStrategyTraitTest extends \PHPUnit_Framework_TestCase
{

    protected $trait;

    public function setUp()
    {
        $this->trait = $this->getObjectForTrait('Matryoshka\Model\Hydrator\Strategy\NullableStrategyTrait');
    }



    public function testIsSetNullable()
    {
        // Default
        $this->assertTrue($this->trait->isNullable());
        $this->assertSame($this->trait, $this->trait->setNullable(false));
        $this->assertFalse($this->trait->isNullable());
        $this->assertSame($this->trait, $this->trait->setNullable(true));
        $this->assertTrue($this->trait->isNullable());
    }


}