<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\TestAsset;

use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\HydratorAwareInterface;
use Zend\Hydrator\HydratorAwareTrait;

/**
 * Class HydratorAwareObject
 */
class HydratorAwareObject extends \ArrayObject implements HydratorAwareInterface
{
    use HydratorAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ArraySerializable();
        }
        return $this->hydrator;
    }
}
