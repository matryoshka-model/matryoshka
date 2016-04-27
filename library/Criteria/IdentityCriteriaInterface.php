<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Object\IdentityAwareInterface;
/**
 * Interface IdentityCriteriaInterface
 *
 * A criteria implementing this interface refers to a single entity indentified 
 * by the identity field.
 */
interface IdentityCriteriaInterface extends CriteriaInterface, IdentityAwareInterface
{
}
