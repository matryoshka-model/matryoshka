<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use ArrayIterator;
use Matryoshka\Model\Exception;

/**
 *
 *
 */
class BufferedResultSet extends AbstractResultSet implements ResultSetAggregateInterface
{
    /**
     * @var AbstractResultSet
     */
    protected $resultSet;

    /**
     * @var array
     */
    protected $buffer = [];

    /**
     * @param AbstractResultSet $resultSet
     */
    public function __construct(AbstractResultSet $resultSet)
    {
        if ($resultSet instanceof BufferedResultSet) {
            throw new Exception\InvalidArgumentException(
                'ResultSet is already buffered'
            );
        }

        $this->resultSet = $resultSet;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize($dataSource)
    {
        $this->position = 0;
        $this->count    = null;
        $this->buffer   = [];
        $this->resultSet->initialize($dataSource);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setObjectPrototype($objectPrototype)
    {
        $this->resultSet->setObjectPrototype($objectPrototype);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectPrototype()
    {
        return $this->resultSet->getObjectPrototype();
    }

    /**
     * Get the data source used to create the result set
     *
     * @return null|Iterator
     */
    public function getDataSource()
    {
        return $this->resultSet->getDataSource();
    }

    /**
     * @throws Exception\RuntimeException
     */
    protected function ensurePosition()
    {
        if ($this->resultSet->key() != $this->key()) {
             throw new Exception\RuntimeException(
                'Buffering can not work while the aggregate resultset is being iterated over'
            );
        }
    }

    /**
     * Iterator: move pointer to next item
     *
     * @return void
     */
    public function next()
    {
        if (!isset($this->buffer[++$this->position])
            && $this->position != $this->resultSet->key() // FIXME: check
        ) {
            $this->resultSet->next();
        }
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
     * @return mixed
     */
    public function current()
    {
        if (isset($this->buffer[$this->position])) {
            return $this->buffer[$this->position];
        }
        $this->ensurePosition();
        $data = $this->resultSet->current();
        $this->buffer[$this->position] = $data;
        return $data;
    }

    /**
     * Iterator: is pointer valid?
     *
     * @return bool
     */
    public function valid()
    {
        if (isset($this->buffer[$this->position])) {
            return (bool) $this->buffer[$this->position];
        }
        $this->ensurePosition();
        return $this->resultSet->valid();
    }

    /**
     * Iterator: rewind
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Countable: return count of items
     *
     * @return int
     * @throws Exception\RuntimeException
     */
    public function count()
    {
        if ($this->count === null) {
            $this->count = $this->resultSet->count();
        }
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    protected function itemToArray($item)
    {
        return $this->resultSet->itemToArray($item);
    }

}