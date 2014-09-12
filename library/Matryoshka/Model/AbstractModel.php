<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Zend\Stdlib\Hydrator\AbstractHydrator;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Paginator\AdapterAggregateInterface as PaginatorAdapterAggregateInterface;
use Matryoshka\Model\Criteria\PaginatorCriteriaInterface;

/**
 * Class AbstractModel
 */
abstract class AbstractModel implements
    ModelInterface,
    HydratorAwareInterface,
    InputFilterAwareInterface,
    PaginatorAdapterAggregateInterface
{
    use HydratorAwareTrait;
    use InputFilterAwareTrait;
    use DataGatewayAwareTrait;

    /**
     * @var PaginatorCriteriaInterface
     */
    protected $paginatorCriteria;

    /**
     * ResultSet Prototype
     *
     * @var ResultSetInterface
     */
    protected $resultSetPrototype;

    /**
     * {@inheritdoc}
     */
    public function getHydrator()
    {
        if (!$this->hydrator && $this->getObjectPrototype() instanceof HydratorAwareInterface) {
            $this->hydrator = $this->getObjectPrototype()->getHydrator();
        }
        return $this->hydrator;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            if ($this->getObjectPrototype() instanceof InputFilterAwareInterface) {
                $this->inputFilter = $this->getObjectPrototype()->getInputFilter();
            } else {
                throw new Exception\RuntimeException(
                    'InputFilter must be set or the object prototype must be an instance of InputFilterAwareInterface'
                );
            }
        }
        return $this->inputFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultSetPrototype()
    {
        return $this->resultSetPrototype;
    }

    /**
     * Set ResultSet Prototype
     *
     * @param ResultSetInterface $resultSet
     * @return $this
     */
    protected function setResultSetPrototype(ResultSetInterface $resultSet)
    {
        $this->resultSetPrototype = $resultSet;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectPrototype()
    {
        $resultSetPrototype = $this->getResultSetPrototype();
        if ($resultSetPrototype) {
            return $resultSetPrototype->getObjectPrototype();
        }

        throw new Exception\RuntimeException(
            'ResultSet must be set and must have an object prototype'
        );
    }

    /**
     * Create
     *
     * @return object
     */
    public function create()
    {
        return clone $this->getObjectPrototype();
    }

    /**
     * Find
     *
     * @param CriteriaInterface $criteria
     * @return ResultSetInterface
     */
    public function find(CriteriaInterface $criteria)
    {
        $result = $criteria->apply($this);
        $resultSet = clone $this->getResultSetPrototype();
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * Save
     *
     * Inserts or updates data
     *
     * @param WriteCriteriaInterface $criteria
     * @param HydratorAwareInterface|object|array $dataOrObject
     * @throws Exception\RuntimeException
     * @return null|int
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject)
    {

        $hydrator = $this->getHydrator();

        if (!$hydrator && ($dataOrObject instanceof HydratorAwareInterface)) {
            $hydrator = $dataOrObject->getHydrator();
        }

        if ($hydrator && is_object($dataOrObject)) {
            $data = $hydrator->extract($dataOrObject);
        } else {

            if (is_array($dataOrObject)) {
                $data = $dataOrObject;
            } elseif (method_exists($dataOrObject, 'toArray')) {
                $data = $dataOrObject->toArray();
            } elseif (method_exists($dataOrObject, 'getArrayCopy')) {
                $data = $dataOrObject->getArrayCopy();
            } else {
                throw new Exception\RuntimeException(
                    'dataOrObject with type ' .
                    gettype($dataOrObject) .
                    ' cannot be casted to an array'
                );
            }

            if ($hydrator) {
                if (!$hydrator instanceof AbstractHydrator) {
                    throw new Exception\RuntimeException(
                        'Hydrator must be an instance of AbstractHydrator' .
                        'in order to extract single value with extractValue method'
                    );
                }
                $data = [];
                foreach ($dataOrObject as $key => $value) {
                    $data[$key] = $hydrator->extractValue($key, $value, $dataOrObject);
                }

            }
        }

        $result = $criteria->applyWrite($this, $data);

        if (!is_integer($result)) {
            $result = null;
        }

        if ($result && $hydrator && is_object($dataOrObject)) {
            $hydrator->hydrate($data, $dataOrObject);
        }

        return $result;
    }

    /**
     * Delete
     *
     * @param DeleteCriteriaInterface $criteria
     * @return null|int
     */
    public function delete(DeletableCriteriaInterface $criteria)
    {
        $result = $criteria->applyDelete($this);
        if (!is_integer($result)) {
            $result = null;
        }
        return $result;
    }


    /**
     * Set the default paginator criteria
     *
     * @param PaginatorCriteriaInterface $criteria
     * @return $this
     */
    public function setPaginatorCriteria(PaginatorCriteriaInterface $criteria)
    {
        $this->paginatorCriteria = $criteria;
        return $this;
    }

    /**
     * Retrive the default paginator criteria
     *
     * @throws Exception\RuntimeException
     * @return PaginatorCriteriaInterface
     */
    public function getPaginatorCriteria()
    {
        if (!$this->paginatorCriteria) {
            throw new Exception\RuntimeException('PaginatorCriteria must be set before use');
        }

        return $this->paginatorCriteria;
    }

    /**
     * {@inheritdoc}
     * @throws Exception\RuntimeException
     */
    public function getPaginatorAdapter(PaginatorCriteriaInterface $criteria = null)
    {
        if (null === $criteria) {
            $criteria = $this->getPaginatorCriteria();
        }

        return $criteria->getPaginatorAdapter($this);
    }
}
