<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

use Matryoshka\Model\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;

/**
 * Class AbstractObject
 *
 * Abstract generic class to create domain model specific objects.
 */
abstract class AbstractObject implements HydratorAwareInterface, InputFilterAwareInterface
{
    use HydratorAwareTrait;
    use InputFilterAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ClassMethods();
        }

        return $this->hydrator;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $this->inputFilter = new InputFilter();
        }

        return $this->inputFilter;
    }
}
