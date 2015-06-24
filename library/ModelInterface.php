<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\PaginableCriteriaInterface;
use Matryoshka\Model\Criteria\ReadableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\Paginator\AdapterAggregateInterface as PaginatorAdapterAggregateInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;

/**
 * Interface ModelInterface
 *
 * Contract for model services definining common features such as CRUD and pagination.
 */
interface ModelInterface extends ModelPrototypeInterface, PaginatorAdapterAggregateInterface
{

    /**
     * Create
     *
     * @return object
     */
    public function create();

    /**
     * Find
     *
     * @param ReadableCriteriaInterface $criteria
     * @return ResultSetInterface
     */
    public function find(ReadableCriteriaInterface $criteria);

    /**
     * Save
     *
     * Inserts or updates data
     *
     * @param WritableCriteriaInterface $criteria
     * @param HydratorAwareInterface|object|array $dataOrObject
     * @return null|int
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject);

    /**
     * Delete
     *
     * @param DeletableCriteriaInterface $criteria
     * @return null|int
     */
    public function delete(DeletableCriteriaInterface $criteria);

    /**
     * {@inheritdoc}
     */
    public function getPaginatorAdapter(PaginableCriteriaInterface $criteria = null);
}
