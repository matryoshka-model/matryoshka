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
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;
use Matryoshka\Model\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\InputFilter\InputFilterAwareTrait;

abstract class AbstractModel implements ModelInterface
{

    use HydratorAwareTrait;
    use InputFilterAwareTrait;

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
     * @param ResultSet $resultSet
     * @return $this
     */
    protected function setResultSetPrototype(ResultSet $resultSet)
    {
        if ($resultSet instanceof HydratorAwareInterface && $this->getHydrator()) {
            $resultSet->setHydrator($this->getHydrator());
        }
        $this->resultSetPrototype = $resultSet;
        return $this;
    }

    /**
     * @return CriteriaInterface
     */
    public function getDefaultCriteria()
    {
        return $this->defaultCriteria;
    }

    /**
     * @param CriteriaInterface $defaultCriteria
     * @return $this
     */
    public function setDefaultCriteria(CriteriaInterface $defaultCriteria)
    {
        $this->defaultCriteria = $defaultCriteria;
        return $this;
    }

    /**
     * @param CriteriaInterface $criteria
     * @return mixed
     */
    protected function processCriteria(CriteriaInterface $criteria = null)
    {
        if (null === $criteria) {
            $criteria = $this->getDefaultCriteria();
        }

        // Bind and excecute persistence
        return $criteria->apply($this);
    }

    /**
     * @param CriteriaInterface $criteria
     * @return ResultSetInterface
     */
    public function find(CriteriaInterface $criteria = null)
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
    public function getPaginatorAdapter(CriteriaInterface $criteria = null)
    {
        //TODO
    }

}