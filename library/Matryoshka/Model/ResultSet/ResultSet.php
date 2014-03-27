<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use ArrayObject;

class ResultSet extends AbstractResultSet
{

    const TYPE_ARRAYOBJECT = 'arrayobject';
    const TYPE_ARRAY  = 'array';

    /**
     * Allowed return types
     *
     * @var array
     */
    protected $allowedReturnTypes = array(
        self::TYPE_ARRAYOBJECT,
        self::TYPE_ARRAY,
    );

    /**
     * @var ArrayObject
     */
    protected $arrayObjectPrototype = null;

    /**
     * Return type to use when returning an object from the set
     *
     * @var ResultSet::TYPE_ARRAYOBJECT|ResultSet::TYPE_ARRAY
     */
    protected $returnType = self::TYPE_ARRAYOBJECT;

    /**
     * Constructor
     *
     * @param string           $returnType
     * @param null|ArrayObject $arrayObjectPrototype
     */
    public function __construct($returnType = self::TYPE_ARRAYOBJECT, $arrayObjectPrototype = null)
    {
        $this->returnType = (in_array($returnType, array(self::TYPE_ARRAY, self::TYPE_ARRAYOBJECT))) ? $returnType : self::TYPE_ARRAYOBJECT;
        if ($this->returnType === self::TYPE_ARRAYOBJECT) {
            $this->setArrayObjectPrototype(($arrayObjectPrototype) ?: new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS));
        }
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
        $this->setArrayObjectPrototype($arrayObjectPrototype);
        return $this;
    }

    /**
     * Get the item object prototype
     *
     * @return ArrayObject
     */
    public function getObjectPrototype()
    {
        return $this->getArrayObjectPrototype();
    }

    /**
     * Set the row object prototype
     *
     * @param  ArrayObject $arrayObjectPrototype
     * @throws Exception\InvalidArgumentException
     * @return ResultSet
     */
    public function setArrayObjectPrototype($arrayObjectPrototype)
    {
        if (!is_object($arrayObjectPrototype)
            || (!$arrayObjectPrototype instanceof ArrayObject && !method_exists($arrayObjectPrototype, 'exchangeArray'))

        ) {
            throw new Exception\InvalidArgumentException('Object must be of type ArrayObject, or at least implement exchangeArray');
        }
        $this->arrayObjectPrototype = $arrayObjectPrototype;
        return $this;
    }

    /**
     * Get the row object prototype
     *
     * @return ArrayObject
     */
    public function getArrayObjectPrototype()
    {
        return $this->arrayObjectPrototype;
    }

    /**
     * Get the return type to use when returning objects from the set
     *
     * @return string
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * @return array|\ArrayObject|null
     */
    public function current()
    {
        $data = parent::current();

        if ($this->returnType === self::TYPE_ARRAYOBJECT && is_array($data)) {
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