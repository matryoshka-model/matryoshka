<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\ResultSet\ResultSetInterface;

use Zend\Paginator\AdapterAggregateInterface as PaginatorAdapterAggregateInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;

interface ModelInterface extends HydratorAwareInterface, PaginatorAdapterAggregateInterface
{

    /**
     * @return mixed
     */
    public function getDataGateway();

    /**
     * @return ResultSetInterface
     */
    public function getResultSetPrototype();

    /**
     * @return CriteriaInterface
     */
    public function getDefaultCriteria();

    /**
     * @param CriteriaInterface|Closure $criteria
     * @return ResultSetInterface
     */
    public function find($criteria = null);

    /**
     * @param CriteriaInterface $criteria
     * @return PaginatorAdapterInterface
     */
    public function getPaginatorAdapter($criteria = null);

}