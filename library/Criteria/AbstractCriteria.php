<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelStubInterface;

/**
 * Class AbstractCriteria
 *
 * A commodity class providing common method for read operations on collections.
 */
abstract class AbstractCriteria implements ReadableCriteriaInterface
{
    /**
     * Limit
     *
     * @var int
     */
    protected $limit;

    /**
     * Offset
     *
     * @var int
     */
    protected $offset;

    /**
     * Set limit
     *
     * @param int|null $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit === null ? null : (int) $limit;
        return $this;
    }

    /**
     * Get limit
     *
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set offset
     *
     * @param int|null $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset === null ? null : (int) $offset;
        return $this;
    }

    /**
     * Get offset
     *
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Apply
     *
     * @param ModelStubInterface $model
     * @return mixed
     */
    abstract public function apply(ModelStubInterface $model);
}
