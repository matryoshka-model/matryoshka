<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet\PrototypeStrategy;

/**
 * Class CloneStrategy
 */
class CloneStrategy implements PrototypeStrategyInterface
{
    /**
     * @param object $objectPrototype
     * @param array $context
     * @return object
     */
    public function createObject($objectPrototype, array $context = null)
    {
        return clone $objectPrototype;
    }
}
