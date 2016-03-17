<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator\Strategy;

use ArrayObject;
use Matryoshka\Model\Exception;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyAwareTrait;
use Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyAwareInterface;

/**
 * Class HasManyStrategy
 */
class HasManyStrategy implements
    StrategyInterface,
    NullableStrategyInterface,
    PrototypeStrategyAwareInterface
{
    use NullableStrategyTrait;
    use PrototypeStrategyAwareTrait;

    /**
     * @var HasOneStrategy
     */
    protected $hasOneStrategy;

    /**
     * @var \ArrayAccess
     */
    protected $arrayObjectPrototype;

    /**
     * Ctor
     *
     * @param HydratorAwareInterface $objectPrototype
     * @param \ArrayAccess $arrayObjectPrototype
     * @param bool $nullable
     */
    public function __construct(
        HydratorAwareInterface $objectPrototype,
        \ArrayAccess $arrayObjectPrototype = null,
        $nullable = true
    ) {
        $this->hasOneStrategy = new HasOneStrategy($objectPrototype, false);
        $this->arrayObjectPrototype = $arrayObjectPrototype ? $arrayObjectPrototype : new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
        $this->setNullable($nullable);
    }

    /**
     * @return HydratorAwareInterface
     */
    public function getObjectPrototype()
    {
        return $this->hasOneStrategy->getObjectPrototype();
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param array|\Traversable|null $value The original value.
     * @return array|null Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if ($this->nullable && $value === null) {
            return null;
        }

        $return = [];
        if (is_array($value) || $value instanceof \Traversable) {
            foreach ($value as $key => $object) {
                $return[$key] = $this->hasOneStrategy->extract($object);
            }
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Value must be null (only if nullable option is enabled), or an array, or a Traversable: "%s" given',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return $return;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param array|\Traversable|null $value The original value.
     * @return \ArrayAccess|null Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if ($this->nullable && $value === null) {
            return null;
        }

        $this->hasOneStrategy->setPrototypeStrategy($this->getPrototypeStrategy());
        $return = clone $this->arrayObjectPrototype;
        if (is_array($value) || $value instanceof \Traversable) {
            foreach ($value as $key => $data) {
                $return[$key] = $this->hasOneStrategy->hydrate($data);
            }
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Value must be null (only if nullable option is enabled), or an array, or a Traversable: "%s" given',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return $return;
    }
}
