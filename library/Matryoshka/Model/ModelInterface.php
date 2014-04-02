<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Criteria\DeletableCriteriaInterface;

/**
 * Interface ModelInterface
 */
interface ModelInterface extends HydratorAwareInterface, InputFilterAwareInterface
{
    /**
     * Get Data Gateway
     * @return mixed
     */
    public function getDataGateway();

    /**
     * Get ResultSet Prototype
     * @return ResultSetInterface
     */
    public function getResultSetPrototype();

    /**
     * Find
     * @param CriteriaInterface|\Closure $criteria
     * @return ResultSetInterface
     */
    public function find(CriteriaInterface $criteria);

    /**
     * @param WriteCriteriaInterface $criteria
     * @param HydratorAwareInterface|object|array $dataOrObject
     * @throws Exception\RuntimeException
     * @return boolean
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject);

    /**
     * @param DeleteCriteriaInterface $criteria
     * @return boolean
     */
    public function delete(DeletableCriteriaInterface $criteria);
}
