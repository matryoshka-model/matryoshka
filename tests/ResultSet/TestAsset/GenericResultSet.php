<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet\TestAsset;

use Matryoshka\Model\ResultSet\AbstractResultSet;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Exception\InvalidArgumentException;

/**
 * Class GenericResultSet
 */
class GenericResultSet extends AbstractResultSet
{
    protected $dataSource = ['test'];

    protected $objectPrototype;

    /**
     * Set the item object prototype
     *
     * @param  object $objectPrototype
     * @throws InvalidArgumentException
     * @return ResultSetInterface
     */
    public function setObjectPrototype($objectPrototype)
    {
        $this->objectPrototype = $objectPrototype;
        return $this;
    }

    /**
     * Get the item object prototype
     *
     * @return mixed
     */
    public function getObjectPrototype()
    {
        return $this->objectPrototype;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
