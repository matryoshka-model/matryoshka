<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\TestAsset;

/**
 * Class ToArrayObject
 */
class ToArrayObject
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function exchangeArray(array $data)
    {
        $this->data = $data;
    }
}
