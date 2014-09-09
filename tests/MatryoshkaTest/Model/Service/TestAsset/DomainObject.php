<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service\TestAsset;

use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\InputFilter\InputFilter;
use Matryoshka\Model\ModelAwareInterface;
use Matryoshka\Model\ModelAwareTrait;

class DomainObject implements
    HydratorAwareInterface,
    InputFilterAwareInterface,
    ModelAwareInterface
{

    use HydratorAwareTrait;
    use InputFilterAwareTrait;
    use ModelAwareTrait;


}
