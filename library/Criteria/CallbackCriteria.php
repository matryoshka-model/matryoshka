<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelStubInterface;

/**
 * Class CallbackCriteria
 *
 * Allows the creation of a read operation through a closure or a callable.
 */
class CallbackCriteria extends AbstractCriteria implements ReadableCriteriaInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Ctor
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ModelStubInterface $model)
    {
        $callback = $this->callback;
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($this);
        }
        return call_user_func($callback, $model);
    }
}
