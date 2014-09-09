<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet\PrototypeStrategy;

use Matryoshka\Model\ResultSet\PrototypeStrategy\CloneStrategy;

class CloneStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateObject()
    {
        $strategy = new CloneStrategy();
        $objectPrototype = new \stdClass();

        $object = $strategy->createObject($objectPrototype);

        $this->assertEquals($objectPrototype, $object);
        $this->assertNotSame($objectPrototype, $object);
    }

}
