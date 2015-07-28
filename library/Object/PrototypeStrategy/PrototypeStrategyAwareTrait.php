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
 * Interface PrototypeStrategyAwareTrait
 */
trait PrototypeStrategyAwareTrait
{

    /**
     * @var PrototypeStrategyInterface
     */
    protected $prototypeStrategy;

    /**
     * Set the prototype strategy
     *
     * @return $this
     */
    public function setPrototypeStrategy(PrototypeStrategyInterface $strategy)
    {
        $this->prototypeStrategy = $strategy;
        return $this;
    }

    /**
     * Get the prototype strategy
     *
     * @return PrototypeStrategyInterface
     */
    public function getPrototypeStrategy()
    {
        if (!$this->prototypeStrategy) {
            $this->setPrototypeStrategy(new CloneStrategy());
        }

        return $this->prototypeStrategy;
    }
}
