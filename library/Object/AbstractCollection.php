<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

use Zend\Stdlib\ArrayObject;
use Matryoshka\Model\Exception;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Stdlib\Hydrator\ArraySerializable;

/**
 * Class AbstractCollection
 *
 * Abstract class for collection of embedded values.
 * validateValue() method must be implemented and must throw an InvalidArgumentException
 * when an a value is not allowed within this collection.
 */
abstract class AbstractCollection extends ArrayObject implements HydratorAwareInterface
{
    use HydratorAwareTrait;

    /**
     * Constructor
     *
     * @param array  $input
     * @param int    $flags
     * @param string $iteratorClass
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($input = [], $flags = self::STD_PROP_LIST, $iteratorClass = 'ArrayIterator')
    {
        $this->validateData($input);
        parent::__construct($input, $flags, $iteratorClass);
    }

    /**
     * @return \Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ArraySerializable();
        }
        return $this->hydrator;
    }

    /**
     * Validate the value
     *
     * Checks that the value passed is allowed within the collection
     *
     * @param mixed $value
     * @throws Exception\InvalidArgumentException
     */
    abstract public function validateValue($value);

    /**
     * Validate an array of values
     *
     * Checks that values passed are allowed within the collection
     *
     * @param array $data
     * @throws Exception\InvalidArgumentException
     */
    public function validateData(array $data)
    {
        foreach ($data as $value) {
            $this->validateValue($value);
        }
    }


    /**
     * {@inheritdoc}
     * @throws Exception\InvalidArgumentException
     */
    public function offsetSet($key, $value)
    {
        $this->validateValue($value);
        return parent::offsetSet($key, $value);
    }

    /**
     * {@inheritdoc}
     * @throws Exception\InvalidArgumentException
     */
    public function append($value)
    {
        $this->validateValue($value);
        return parent::append($value);
    }

    /**
     * Exchange the array for another one.
     *
     * @param  array|ArrayObject $data
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function exchangeArray($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new Exception\InvalidArgumentException(
                'Passed variable is not an array or object, using empty array instead'
            );
        }

        if (is_object($data) && ($data instanceof parent || $data instanceof \ArrayObject)) {
            $data = $data->getArrayCopy();
        }

        if (!is_array($data)) {
            $data = (array) $data;
        }

        $this->validateData($data);

        $storage = $this->storage;
        $this->storage = $data;
        return $storage;
    }
}
