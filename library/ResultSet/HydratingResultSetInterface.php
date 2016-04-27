<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet;

use Zend\Stdlib\Hydrator\HydratorAwareInterface;

/**
 * Interface HydratingResultSetInterface
 */
interface HydratingResultSetInterface extends ResultSetInterface, HydratorAwareInterface
{
}
