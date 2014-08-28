<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet\PrototypeStrategy;

class CloneStrategy implements PrototypeStrategyInterface
{

    /**
     * @param object $objectPrototype
     * @param array $context
     */
    public function createObject($objectPrototype, array $context = null)
    {
        return clone $objectPrototype;
    }

}