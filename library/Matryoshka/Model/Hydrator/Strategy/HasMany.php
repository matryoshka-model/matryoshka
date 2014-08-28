<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator\Strategy;

use ArrayObject;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Matryoshka\Model\Exception;

class HasMany implements StrategyInterface
{

    /**
     * @var HasOne
     */
    protected $hasOneStrategy;

    /**
     * @var \ArrayAccess
     */
    protected $arrayObjectPrototype;

    /**
     * Ctor
     *
     * @param $objectPrototype
     */
    public function __construct(HydratorAwareInterface $objectPrototype, \ArrayAccess $arrayObjectPrototype = null)
    {
        $this->hasOneStrategy = new HasOne($objectPrototype);
        $this->arrayObjectPrototype = $arrayObjectPrototype ? $arrayObjectPrototype : new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
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
     * @param mixed $value The original value.
     * @param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        $return = array();
        if (is_array($value) || $value instanceof \Traversable) {
            foreach ($value as $key => $object) {
                $return[$key] = $this->hasOneStrategy->extract($object);
            }
        } else {
            throw new Exception\InvalidArgumentException("Value must be an array or Travesable, " . gettype($value) . " given");
        }

        return $return;
     }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        $return = clone $this->arrayObjectPrototype;
        if (is_array($value) || $value instanceof \Traversable) {
            foreach ($value as $key => $data) {
                $return[$key] = $this->hasOneStrategy->hydrate($data);
            }
        } else {
            throw new Exception\InvalidArgumentException("Value must be an array or Travesable, " . gettype($value) . " given");
        }

        return $return;
    }
}