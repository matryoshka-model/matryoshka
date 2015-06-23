<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use ArrayObject;
use Matryoshka\Model\Exception;

/**
 * Class ResultSet
 */
class ArrayObjectResultSet extends AbstractResultSet
{
    /**
     * @var ArrayObject|object
     */
    protected $arrayObjectPrototype = null;

    /**
     * Constructor
     *
     * @param ArrayObject $arrayObjectPrototype
     */
    public function __construct($arrayObjectPrototype = null)
    {
        $this->setObjectPrototype(($arrayObjectPrototype) ?: new ArrayObject([], ArrayObject::ARRAY_AS_PROPS));
    }

    /**
     * Set the item object prototype
     *
     * @param  object $objectPrototype
     * @throws Exception\InvalidArgumentException
     * @return ResultSetInterface
     */
    public function setObjectPrototype($objectPrototype)
    {
        if (!is_object($objectPrototype)
            || (!$objectPrototype instanceof ArrayObject && !method_exists($objectPrototype, 'exchangeArray'))

        ) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Object must be of type %s, or at least implement %s',
                'ArrayObject',
                'exchangeArray'
            ));
        }
        $this->arrayObjectPrototype = $objectPrototype;
        return $this;
    }

    /**
     * Get the item object prototype
     *
     * @return ArrayObject|object
     */
    public function getObjectPrototype()
    {
        return $this->arrayObjectPrototype;
    }

    /**
     * @return ArrayObject|object|null
     */
    public function current()
    {
        $data = parent::current();

        if (is_array($data)) {
            /** @var $ao ArrayObject */
            $ao = clone $this->arrayObjectPrototype;
            if ($ao instanceof ArrayObject || method_exists($ao, 'exchangeArray')) {
                $ao->exchangeArray($data);
            }
            return $ao;
        }

        return $data;
    }
}
