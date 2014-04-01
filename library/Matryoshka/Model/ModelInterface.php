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
}
