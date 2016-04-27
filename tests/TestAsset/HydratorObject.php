<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\TestAsset;

use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class HydratorObject
 */
class HydratorObject implements HydratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function extract($object)
    {
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate(array $data, $object)
    {
        return ['one', 'two', 'three'];
    }
}
