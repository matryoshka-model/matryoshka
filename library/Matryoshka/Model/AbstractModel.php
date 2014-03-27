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

use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;

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
     * @param ResultSetInterface $resultSet
     * @return $this
     */
    protected function setResultSetPrototype(ResultSetInterface $resultSet)
    {
        if ($resultSet instanceof HydratorAwareInterface && $this->getHydrator()) {
            $resultSet->setHydrator($this->getHydrator());
        }
        $this->resultSetPrototype = $resultSet;
        return $this;
    }

    /**
     * @return object
     */
    public function getObjectPrototype()
    {
        $resultSetPrototype = $this->getResultSetPrototype();
        if ($resultSetPrototype) {
            return $resultSetPrototype->getObjectPrototype();
        }
        return null;
    }

    /**
     * @param CriteriaInterface $criteria
     * @return mixed
     */
    protected function processCriteria(CriteriaInterface $criteria)
    {
        // Bind and excecute persistence
        return $criteria->apply($this);
    }

    /**
     * @return object
     */
    public function create()
    {
        return clone $this->getObjectPrototype();
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
}