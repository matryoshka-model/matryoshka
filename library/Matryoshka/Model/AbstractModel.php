<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
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

/**
 * Class AbstractModel
 */
abstract class AbstractModel implements ModelInterface
{
    use HydratorAwareTrait;
    use InputFilterAwareTrait;
    use DataGatewayAwareTrait;

    /**
     * ResultSet Prototype
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
     * @param ResultSetInterface $resultSet
     * @return $this
     */
    protected function setResultSetPrototype(ResultSetInterface $resultSet)
    {
        $this->resultSetPrototype = $resultSet;
        return $this;
    }

    /**
     * Get Object prototype
     * @return object
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
     * Process Criteria
     * @param CriteriaInterface $criteria
     * @return mixed
     */
    protected function processCriteria(CriteriaInterface $criteria)
    {
        return $criteria->apply($this);
    }

    /**
     * Create
     * @return object
     */
    public function create()
    {
        return clone $this->getObjectPrototype();
    }

    /**
     * {@inheritdoc}
     */
    public function find(CriteriaInterface $criteria)
    {
        $result = $this->processCriteria($criteria);
        $resultSet = clone $this->getResultSetPrototype();
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * @param WriteCriteriaInterface $criteria
     * @param unknown $dataOrObject
     * @throws Exception\RuntimeException
     * @return boolean
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject)
    {
        if ($dataOrObject instanceof HydratorAwareInterface) {
            $data = $dataOrObject->getHydrator()->extract($dataOrObject);
        } else {
            if (is_array($dataOrObject)) {
                $data = $dataOrObject;
            } elseif (method_exists($dataOrObject, 'toArray')) {
                $data = $dataOrObject->toArray();
            } elseif (method_exists($dataOrObject, 'getArrayCopy')) {
                $data = $dataOrObject->getArrayCopy();
            } else {
                throw new Exception\RuntimeException(
                    '$dataOrObject must be an HydratorAwareInterface or an array, with type ' . gettype($dataOrObject) . ' cannot be cast to an array'
                );
            }

            $hydrator = $this->getHydrator();
            if ($hydrator) {
                if (!$hydrator instanceof AbstractHydrator) {
                    throw new Exception\RuntimeException(
                        'Hydrator must be an instance of AbstractHydrator in order to extract single value with extractValue method'
                    );
                }
                $data = array();
                foreach ($dataOrObject as $key => $value) {
                    $data[$key] = $hydrator->extractValue($key, $value, $dataOrObject);
                }
            }
        }

        return (bool) $criteria->applyWrite($this, $data);
    }

    /**
     * @param DeleteCriteriaInterface $criteria
     * @return boolean
     */
    public function delete(DeletableCriteriaInterface $criteria)
    {
        return (bool) $criteria->applyDelete($this);
    }
}
