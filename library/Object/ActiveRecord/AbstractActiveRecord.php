<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\ActiveRecord;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelAwareInterface;
use Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria;
use Matryoshka\Model\ModelAwareTrait;
use Matryoshka\Model\Object\AbstractObject;
use Matryoshka\Model\Object\InitializableInterface;

/**
 *
 *
 */
abstract class AbstractActiveRecord extends AbstractObject implements
    ModelAwareInterface,
    ActiveRecordInterface
{

    use ModelAwareTrait;

    /**
     * @var AbstractCriteria
     */
    protected $activeRecordCriteriaPrototype;

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
            throw new Exception\RuntimeException('An Active Record Criteria Prototype must be set prior to calling save()');
        }

        if (!$this->getModel()) {
            throw new Exception\RuntimeException('A Model must be set prior to calling save()');
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
            throw new Exception\RuntimeException('An ID must be set prior to calling delete()');
        }

        if (!$this->activeRecordCriteriaPrototype) {
            throw new Exception\RuntimeException('An Active Record Criteria Prototype must be set prior to calling delete()');
        }

        if (!$this->getModel()) {
            throw new Exception\RuntimeException('A Model must be set prior to calling delete()');
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
