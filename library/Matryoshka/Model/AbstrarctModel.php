<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;

abstract class AbstractModel implements ModelInterface
{

    use HydratorAwareTrait;

    /**
     * @var mixed
     */
    protected $dataGateway;

    /**
     * @var ResultSetInterface
     */
    protected $resultSetPrototype;

    /**
     * @var CriteriaInterface
     */
    protected $defaultCriteria;

    /**
     * @return mixed
     */
    public function getDataGateway()
    {
        return $this->dataGateway;
    }

    /**
     * @return ResultSetInterface
     */
    public function getResultSetPrototype()
    {
        return $this->resultSetPrototype;
    }

    /**
     * @return CriteriaInterface
     */
    public function getDefaultCriteria()
    {
        return $this->defaultCriteria;
    }

    protected function processCriteria($criteria)
    {
        if (null === $criteria) {
            $criteria = $this->getDefaultCriteria();
        }

        if ($criteria instanceof \Closure) {
            $closure = $criteria;
            $criteria = clone $this->getDefaultCriteria();
            $closure = $closure($criteria);
        }

        if (! $criteria instanceof CriteriaInterface) {
            throw new Exception\UnexpectedValueException('$criteria must be an instance of Matryoshka\Model\Criteria\CriteriaInterface');
        }

        return $criteria->apply($this);
    }

    /**
     * @param CriteriaInterface $criteria
     * @return ResultSetInterface
     */
    public function find($criteria = null)
    {
        $result = $this->processCriteria($criteria);
        $resultSet = clone $this->getResultSetPrototype();
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * @param CriteriaInterface $criteria
     * @return PaginatorAdapterInterface
     */
    public function getPaginatorAdapter($criteria = null)
    {
        //TODO
    }
}