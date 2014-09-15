<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelInterface;

/**
 * Class AbstractCriteria
 */
abstract class AbstractCriteria implements ReadableCriteriaInterface
{
    /**
     * Limit
     * @var int
     */
    protected $limit;

    /**
     * Offset
     * @var int
     */
    protected $offset;

    /**
     * Limit
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * Offset
     * @param int $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = (int)$offset;
        return $this;
    }

    /**
     * Apply
     * @param ModelInterface $model
     * @return mixed
     */
    abstract public function apply(ModelInterface $model);
}
