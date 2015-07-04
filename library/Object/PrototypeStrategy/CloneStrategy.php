<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\PrototypeStrategy;

use Matryoshka\Model\Exception;

/**
 * Class CloneStrategy
 *
 * Strategy for the creation of objects by cloning their prototype.
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
        if (!is_object($objectPrototype)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Object prototype must be an object, given "%s"',
                gettype($objectPrototype)
            ));
        }
        return clone $objectPrototype;
    }
}
