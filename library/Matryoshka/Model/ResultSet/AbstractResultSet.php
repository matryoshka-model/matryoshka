<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;
use Matryoshka\Model\Exception;

/**
 * Class AbstractResultSet
 */
abstract class AbstractResultSet implements Iterator, ResultSetInterface
{

    /**
     * @var null|int
     */
    protected $count = null;

    /**
     * @var Iterator|IteratorAggregate
     */
    protected $dataSource = null;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * Set the data source for the result set
     *
     * @param  Iterator|IteratorAggregate $dataSource
     * @return ResultSet
     * @throws Exception\InvalidArgumentException
     */
    public function initialize($dataSource)
    {
        $this->count = null;
        $this->position = 0;

        if (is_array($dataSource)) {
            // its safe to get numbers from an array
            $first = current($dataSource);
            reset($dataSource);
            $this->count = count($dataSource);
            $this->dataSource = new ArrayIterator($dataSource);
        } elseif ($dataSource instanceof IteratorAggregate) {
            $this->dataSource = $dataSource->getIterator();
        } elseif ($dataSource instanceof Iterator) {
            $this->dataSource = $dataSource;
        } else {
            throw new Exception\InvalidArgumentException('DataSource provided is not an array, nor does it implement Iterator or IteratorAggregate');
        }

        return $this;
    }

    /**
     * Get the data source used to create the result set
     *
     * @return null|Iterator
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * Iterator: move pointer to next item
     *
     * @return void
     */
    public function next()
    {
        $this->dataSource->next();
        $this->position++;
    }

    /**
     * Iterator: retrieve current key
     *
     * @return mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Iterator: get current item
     *
     * @return array
     */
    public function current()
    {
        return $this->dataSource->current();
    }

    /**
     * Iterator: is pointer valid?
     *
     * @return bool
     */
    public function valid()
    {
        return $this->dataSource->valid();
    }

    /**
     * Iterator: rewind
     *
     * @return void
     */
    public function rewind()
    {
        $this->dataSource->rewind();
        $this->position = 0;
    }

    /**
     * Countable: return count of items
     *
     * @return int
     */
    public function count()
    {
        if ($this->count === null) {
            $this->count = count($this->dataSource);
        }
        return $this->count;
    }

    /**
     * Cast result set to array of arrays
     *
     * @return array
     * @throws Exception\RuntimeException if any item is not castable to an array
     */
    public function toArray()
    {
        $return = array();
        foreach ($this as $item) {
            if (is_array($item)) {
                $return[] = $item;
            } elseif (method_exists($item, 'toArray')) {
                $return[] = $item->toArray();
            } elseif (method_exists($item, 'getArrayCopy')) {
                $return[] = $item->getArrayCopy();
            } else {
                throw new Exception\RuntimeException(
                    'Items as part of this DataSource, with type ' . gettype($item) . ' cannot be cast to an array'
                );
            }
        }
        return $return;
    }
}