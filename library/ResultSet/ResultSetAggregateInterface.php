<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;


/**
 * Interface ResultSetAggregateInterface
 */
interface ResultSetAggregateInterface
{

    /**
     * Return the external ResultSet
     *
     * @return ResultSetInterface
     */
    public function getResultSet();

}
