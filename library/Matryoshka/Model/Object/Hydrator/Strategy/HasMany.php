<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\Hydrator\Strategy;

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
     * Ctor
     *
     * @param $objectPrototype
     */
    public function __construct(HydratorAwareInterface $objectPrototype)
    {
        $this->hasOneStrategy = new HasOne($objectPrototype);
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
        if (is_array($value)) {
            foreach ($value as $object) {
                $return[] = $this->hasOneStrategy->extract($object);
            }
        } else {
            throw new Exception\InvalidArgumentException("Value is not an array, " . gettype($value) . " given");
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
        $return = array();
        $objectPrototype = $this->getObjectPrototype();
        foreach ($value as $data) {
            $return[] = $this->hasOneStrategy->hydrate($data);
        }

        return $return;
    }
}