<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet\TestAsset;

use Matryoshka\Model\ResultSet\AbstractResultSet;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\Db\Exception\InvalidArgumentException;

/**
 * Class GenericResultSet
 */
class GenericResultSet extends AbstractResultSet
{
    protected $dataSource = ['test'];

    /**
     * Set the item object prototype
     *
     * @param  object $objectPrototype
     * @throws InvalidArgumentException
     * @return ResultSetInterface
     */
    public function setObjectPrototype($objectPrototype)
    {
        // TODO: Implement setObjectPrototype() method.
    }

    /**
     * Get the item object prototype
     *
     * @return mixed
     */
    public function getObjectPrototype()
    {
        // TODO: Implement getObjectPrototype() method.
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

}
