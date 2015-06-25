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
 * Interface PrototypeStrategyAwareInterface
 */
interface PrototypeStrategyAwareInterface
{
    /**
     * Set the prototype strategy
     *
     * @return $this
     */
    public function setPrototypeStrategy(PrototypeStrategyInterface $strategy);

    /**
     * Get the prototype strategy
     *
     * @return PrototypeStrategyInterface
     */
    public function getPrototypeStrategy();
}
