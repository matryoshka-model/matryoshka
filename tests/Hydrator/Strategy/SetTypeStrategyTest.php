<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Hydrator\Strategy;

use Matryoshka\Model\Hydrator\Strategy\SetTypeStrategy;

class SetTypeStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrate()
    {
        $strategy = new SetTypeStrategy('int', 'string');

        $hydratedValue = $strategy->hydrate(11);

        $this->assertInternalType('string', $hydratedValue);
        $this->assertSame((string) 11, $hydratedValue);

        $hydratedValue = $strategy->hydrate(null);
        $this->assertNull($hydratedValue);
    }

    public function testExtract()
    {
        $strategy = new SetTypeStrategy('int', 'string');

        $extractedValue = $strategy->extract('11');

        $this->assertInternalType('int', $extractedValue);
        $this->assertSame((int) '11', $extractedValue);

        $extractedValue = $strategy->extract(null);
        $this->assertNull($extractedValue);
    }
}
