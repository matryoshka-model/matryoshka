<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\ActiveRecord;

use Matryoshka\Model\Object\IdentityAwareInterface;

/**
 * Interface ActiveRecordInterface
 */
interface ActiveRecordInterface extends IdentityAwareInterface
{

    /**
     * Save
     */
    public function save();

    /**
     * Delete
     */
    public function delete();
}
