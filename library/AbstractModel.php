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
use Matryoshka\Model\Exception;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Hydrator\AbstractHydrator;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;

/**
 * Class AbstractModel
 *
 * Provides implementations for {@link ModelStubInterface} and {@link ModelInterface} contracts.
 */
abstract class AbstractModel implements
    ModelStubInterface,
    ModelInterface,
    DataGatewayAwareInterface,
    HydratorAwareInterface,
    InputFilterAwareInterface
{
    use DataGatewayAwareTrait;
    use HydratorAwareTrait;
    use InputFilterAwareTrait;

    /**
     * @var PaginableCriteriaInterface
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
                throw new Exception\RuntimeException(sprintf(
                    'InputFilter must be set or the object prototype must be an instance of "%s"',
                    InputFilterAwareInterface::class
                ));
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
        if ($resultSetPrototype && ($objectPrototype = $resultSetPrototype->getObjectPrototype())) {
            if ($objectPrototype instanceof ModelAwareInterface) {
                $objectPrototype->setModel($this);
            }
            return $objectPrototype;
        }

        throw new Exception\RuntimeException(
            'ResultSet must be set and must have an object prototype'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function find(ReadableCriteriaInterface $criteria)
    {
        // Ensure that object and resultset prototypes have been set
        $this->getObjectPrototype();

        $result = $criteria->apply($this);
        $resultSet = clone $this->getResultSetPrototype();
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * {@inheritdoc}
     *
     * @param WritableCriteriaInterface $criteria
     * @param HydratorAwareInterface|object|array $dataOrObject
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @return null|int
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject)
    {
        $isObject = is_object($dataOrObject);
        $hydrator = $this->getHydrator();

        if ($isObject) {

            // Check if the object is valid for this model
            $objectPrototype = $this->getObjectPrototype();
            if (!$dataOrObject instanceof $objectPrototype) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '$dataOrObject with type "%s" is not valid for this model, expected an instance of "%s"',
                    get_class($dataOrObject),
                    get_class($objectPrototype)
                ));
            }

            // Inject the model
            if ($dataOrObject instanceof ModelAwareInterface) {
                $dataOrObject->setModel($this);
            }

            // Use the object hydrator as fallback if a model hydrator is not available
            if (!$hydrator && ($dataOrObject instanceof HydratorAwareInterface)) {
                $hydrator = $dataOrObject->getHydrator();
            }

            // Extract data
            if ($hydrator) {
                $data = $hydrator->extract($dataOrObject);
            } elseif (method_exists($dataOrObject, 'toArray')) {
                $data = $dataOrObject->toArray();
            } elseif (method_exists($dataOrObject, 'getArrayCopy')) {
                $data = $dataOrObject->getArrayCopy();
            }
        } elseif (is_array($dataOrObject)) {

            // If an hydrator was provided, we can still use it
            // to convert each array member
            if ($hydrator) {
                if (!$hydrator instanceof AbstractHydrator) {
                    throw new Exception\RuntimeException(
                        'Hydrator must be an instance of AbstractHydrator' .
                        'in order to extract single value with extractValue method'
                    );
                }
                $data = [];
                $context = (object) $dataOrObject;
                foreach ($dataOrObject as $key => $value) {
                    $data[$key] = $hydrator->extractValue($key, $value, $context);
                }
            } else {
                $data = $dataOrObject;
            }
        }

        if (!isset($data)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '$dataOrObject with type "%s" cannot be casted to an array',
                $isObject ? get_class($dataOrObject) : gettype($dataOrObject)
            ));
        }

        $result = $criteria->applyWrite($this, $data);

        if (!is_integer($result)) {
            $result = null;
        }

        // Make sure data and original data are in sync after save
        if ($result && $hydrator && $isObject) {
            $hydrator->hydrate($data, $dataOrObject);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
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
     * @param PaginableCriteriaInterface $criteria
     * @return $this
     */
    public function setPaginatorCriteria(PaginableCriteriaInterface $criteria)
    {
        $this->paginatorCriteria = $criteria;
        return $this;
    }

    /**
     * Retrive the default paginator criteria
     *
     * @throws Exception\RuntimeException
     * @return PaginableCriteriaInterface
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
     *
     * @throws Exception\RuntimeException
     */
    public function getPaginatorAdapter(PaginableCriteriaInterface $criteria = null)
    {
        if (null === $criteria) {
            $criteria = $this->getPaginatorCriteria();
        }

        return $criteria->getPaginatorAdapter($this);
    }
}
