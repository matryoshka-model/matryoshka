<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\ActiveRecord;

use Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria;
use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelAwareInterface;
use Matryoshka\Model\ModelAwareTrait;
use Matryoshka\Model\Object\AbstractObject;

/**
 * Class AbstractActiveRecord
 *
 * Abstract class aimed to the implementation of the ActiveRecord pattern.
 */
abstract class AbstractActiveRecord extends AbstractObject implements
    ModelAwareInterface,
    ActiveRecordInterface
{
    use ModelAwareTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var AbstractCriteria
     */
    protected $activeRecordCriteriaPrototype;

    /**
     * Set Id
     *
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Active Record Criteria Prototype
     *
     * @param AbstractCriteria $criteria
     * @return $this
     */
    public function setActiveRecordCriteriaPrototype(AbstractCriteria $criteria)
    {
        $this->activeRecordCriteriaPrototype = clone $criteria;
        return $this;
    }

    /**
     * Save
     *
     * @return null|int
     * @throws Exception\RuntimeException
     */
    public function save()
    {
        if (!$this->activeRecordCriteriaPrototype) {
            throw new Exception\RuntimeException(sprintf(
                'An Active Record Criteria Prototype must be set prior to calling %s',
                __FUNCTION__
            ));
        }

        if (!$this->getModel()) {
            throw new Exception\RuntimeException(sprintf('A Model must be set prior to calling %s', __FUNCTION__));
        }

        $criteria = $this->activeRecordCriteriaPrototype;
        $criteria->setId($this->getId());
        $result = $this->getModel()->save($criteria, $this);
        return $result;
    }

    /**
     * Delete
     *
     * @return null|int
     * @throws Exception\RuntimeException
     */
    public function delete()
    {
        if (!$this->getId()) {
            throw new Exception\RuntimeException(sprintf('An ID must be set prior to calling %s', __FUNCTION__));
        }

        if (!$this->activeRecordCriteriaPrototype) {
            throw new Exception\RuntimeException(sprintf(
                'An Active Record Criteria Prototype must be set prior to calling %s',
                __FUNCTION__
            ));
        }

        if (!$this->getModel()) {
            throw new Exception\RuntimeException(sprintf('A Model must be set prior to calling %s', __FUNCTION__));
        }

        $criteria = $this->activeRecordCriteriaPrototype;
        $criteria->setId($this->getId());
        $result = $this->getModel()->delete($criteria);
        return $result;
    }

    /**
     * Get
     *
     * @param $name
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function __get($name)
    {
        throw new Exception\InvalidArgumentException(sprintf(
            '"%s" is not a valid field for this object',
            $name
        ));
    }

    /**
     * Set
     *
     * @param string $name
     * @param mixed $value
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function __set($name, $value)
    {
        throw new Exception\InvalidArgumentException(sprintf(
            '"%s" is not a valid field for this object',
            $name
        ));
    }

    /**
     * Unset
     *
     * @param string $name
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function __unset($name)
    {
        throw new Exception\InvalidArgumentException(sprintf(
            '"%s" is not a valid field for this object',
            $name
        ));
    }
}
