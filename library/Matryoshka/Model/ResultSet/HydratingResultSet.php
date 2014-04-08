<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use ArrayObject;
use Matryoshka\Model\Exception;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;

/**
 * Class HydratingResultSet
 */
class HydratingResultSet extends AbstractResultSet implements HydratorAwareInterface
{
    use HydratorAwareTrait;

    /**
     * @var null
     */
    protected $objectPrototype = null;

    /**
     * Constructor
     *
     * @param  null|HydratorInterface $hydrator
     * @param  null|object $objectPrototype
     */
    public function __construct(HydratorInterface $hydrator = null, $objectPrototype = null)
    {
        $this->setHydrator(($hydrator) ?: new ArraySerializable);
        $this->setObjectPrototype(($objectPrototype) ?: new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS));
    }

    /**
     * Set the row object prototype
     *
     * @param  object $objectPrototype
     * @throws Exception\InvalidArgumentException
     * @return ResultSet
     */
    public function setObjectPrototype($objectPrototype)
    {
        if (!is_object($objectPrototype)) {
            throw new Exception\InvalidArgumentException(
                'An object must be set as the object prototype, a ' . gettype($objectPrototype) . ' was provided.'
            );
        }

        if ($objectPrototype instanceof HydratorAwareInterface && $objectPrototype->getHydrator()) {
            $this->setHydrator($objectPrototype->getHydrator());
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
     * Iterator: get current item
     *
     * @return object|null
     */
    public function current()
    {
        $data = $this->dataSource->current();
        $object = is_array($data) ? $this->hydrator->hydrate($data, clone $this->objectPrototype) : null;
        return $object;
    }

    /**
     * Cast result set to array of arrays
     *
     * @return array
     */
    public function toArray()
    {
        $return = array();
        foreach ($this as $item) {
            $return[] = $this->getHydrator()->extract($item);
        }
        return $return;
    }
}