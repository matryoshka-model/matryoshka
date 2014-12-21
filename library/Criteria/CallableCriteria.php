<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelInterface;

/**
 * Class CallableCriteria
 */
class CallableCriteria extends AbstractCriteria implements ReadableCriteriaInterface
{
    /**
     * Callable
     * @var mixed
     */
    protected $callable;

    /**
     * Ctor
     * @param $callable
     */
    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ModelInterface $model)
    {
        return call_user_func($this->callable, $model);
    }
}
