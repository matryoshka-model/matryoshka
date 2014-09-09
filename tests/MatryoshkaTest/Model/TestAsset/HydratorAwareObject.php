<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\TestAsset;

use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Stdlib\Hydrator\ArraySerializable;

class HydratorAwareObject extends \ArrayObject implements HydratorAwareInterface
{
    use HydratorAwareTrait;

    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ArraySerializable();
        }
        return $this->hydrator;
    }
}
