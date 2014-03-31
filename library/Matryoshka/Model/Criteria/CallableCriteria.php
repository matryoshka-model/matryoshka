<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelInterface;

class CallableCriteria extends AbstractCriteria implements CriteriaInterface
{
    /**
     * @var mixed
     */
    protected $callable;

    /**
     * @param $callable
     */
    function __construct($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function apply(ModelInterface $model)
    {
        return call_user_func($this->callable, $model);
    }
}