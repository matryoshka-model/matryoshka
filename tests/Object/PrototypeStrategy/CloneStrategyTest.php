<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object\PrototypeStrategy;

use Matryoshka\Model\Object\PrototypeStrategy\CloneStrategy;

/**
 * Class CloneStrategyTest
 */
class CloneStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateObject()
    {
        $strategy = new CloneStrategy;
        $objectPrototype = new \stdClass;

        $object = $strategy->createObject($objectPrototype);

        $this->assertEquals($objectPrototype, $object);
        $this->assertNotSame($objectPrototype, $object);
    }
}
