<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelInterface;
use Matryoshka\Model\Exception;

/**
 * Class CallbackCriteria
 */
class CallbackCriteria extends AbstractCriteria implements ReadableCriteriaInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Ctor
     * @param callable $callable
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ModelInterface $model)
    {
        $callback = $this->callback;
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($this);
        }
        return call_user_func($callback, $model);
    }
}
