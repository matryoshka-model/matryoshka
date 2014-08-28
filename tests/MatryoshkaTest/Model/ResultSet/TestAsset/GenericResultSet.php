<?php
/**
 * Created by visa
 * Date:  28/08/14 12.32
 * Class: GenericResultSet.php
 */

namespace MatryoshkaTest\Model\ResultSet\TestAsset;


use Matryoshka\Model\ResultSet\AbstractResultSet;
use Matryoshka\Model\ResultSet\Exception;
use Matryoshka\Model\ResultSet\ResultSetInterface;

class GenericResultSet extends AbstractResultSet
{
    protected $dataSource = ['test'];

    /**
     * Set the item object prototype
     *
     * @param  object $objectPrototype
     * @throws Exception\InvalidArgumentException
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

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

} 