<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use ArrayObject;
use Matryoshka\Model\Exception;
use Matryoshka\Model\ResultSet\PrototypeStrategy\CloneStrategy;
use Matryoshka\Model\ResultSet\PrototypeStrategy\PrototypeStrategyInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class HydratingResultSet
 */
class HydratingResultSet extends AbstractResultSet implements HydratingResultSetInterface
{
    use HydratorAwareTrait;

    /**
     * @var object
     */
    protected $objectPrototype = null;

    /**
     * @var PrototypeStrategyInterface
     */
    protected $prototypeStrategy = null;

    /**
     * Constructor
     *
     * @param  null|HydratorInterface $hydrator
     * @param  null|object $objectPrototype
     */
    public function __construct(HydratorInterface $hydrator = null, $objectPrototype = null)
    {
        if (!$hydrator && $objectPrototype instanceof HydratorAwareInterface) {
            $hydrator = $objectPrototype->getHydrator();
        }
        $this->setHydrator(($hydrator) ?: new ArraySerializable);
        $this->setObjectPrototype(($objectPrototype) ?: new ArrayObject([], ArrayObject::ARRAY_AS_PROPS));
    }

    /**
     * Set the row object prototype
     *
     * @param  object $objectPrototype
     * @throws Exception\InvalidArgumentException
     * @return HydratingResultSet
     */
    public function setObjectPrototype($objectPrototype)
    {
        if (!is_object($objectPrototype)) {
            throw new Exception\InvalidArgumentException(
                'An object must be set as the object prototype, a ' . gettype($objectPrototype) . ' was provided.'
            );
        }

        $this->objectPrototype = $objectPrototype;
        return $this;
    }

    /**
     * Get the item object prototype
     *
     * @return object
     */
    public function getObjectPrototype()
    {
        return $this->objectPrototype;
    }

    /**
     * Set the prototype strategy
     *
     * @return HydratingResultSet
     */
    public function setPrototypeStrategy(PrototypeStrategyInterface $strategy)
    {
        $this->prototypeStrategy = $strategy;
        return $this;
    }

    /**
     * Get prototype strategy
     *
     * @return CloneStrategy
     */
    public function getPrototypeStrategy()
    {
        if (null === $this->prototypeStrategy) {
            $this->setPrototypeStrategy(new CloneStrategy());
        }

        return $this->prototypeStrategy;
    }

    /**
     * Iterator: get current item
     *
     * @return object|null
     */
    public function current()
    {
        $data = $this->dataSource->current();
        $object = null;

        if (is_array($data)) {
            $object = $this->getPrototypeStrategy()->createObject($this->getObjectPrototype(), $data);
            $this->getHydrator()->hydrate($data, $object);
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    protected function itemToArray($item)
    {
        return $this->getHydrator()->extract($item);
    }

}
