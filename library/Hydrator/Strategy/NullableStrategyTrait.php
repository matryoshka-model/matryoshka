<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator\Strategy;

trait NullableStrategyTrait
{

    /**
     * @var bool
     */
    protected $nullable = true;

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     * @return $this
     */
    public function setNullable($nullable)
    {
        $this->nullable = (bool) $nullable;
        return $this;
    }
}
