<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;

/**
 * Interface ModelPrototypeInterface
 *
 * Contract that defines the prototypes identifying the model concept itself.
 */
interface ModelPrototypeInterface
{
    /**
     * Retrieve object prototype
     *
     * @return object
     */
    public function getObjectPrototype();

    /**
     * Retrieve ResultSet prototype
     *
     * @return ResultSetInterface
     */
    public function getResultSetPrototype();
}
