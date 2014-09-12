<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Stdlib\Hydrator\ObjectProperty;

/**
 * Abstract generic class to use as the entity model. Set minimum of interface for use correctly Matryoshka library
 *
 * @author antonio
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
            $this->hydrator = new ObjectProperty();
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
