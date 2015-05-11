<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

/**
 * Interface IdentityAwareInterface
 */
interface IdentityAwareInterface
{

    /**
     * Set Id
     * @param mixed $id
     * @return $this
     */
    public function setId($id);


    /**
     * Get Id
     * @return mixed
     */
    public function getId();
}
