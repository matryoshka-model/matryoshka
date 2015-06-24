<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\PrototypeStrategy;

/**
 * Interface PrototypeStrategyInterface
 *
 * Contract for the creation of objects from their prototype.
 */
interface PrototypeStrategyInterface
{
    /**
     * @param object $objectPrototype
     * @param array $context
     */
    public function createObject($objectPrototype, array $context = null);
}
