<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as ZendClassMethods;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;

/**
 * Class ClassMethods
 */
class ClassMethods extends ZendClassMethods
{
    /**
     * {@inheritdoc}
     */
    public function __construct($underscoreSeparatedKeys = false)
    {
        parent::__construct($underscoreSeparatedKeys);
        // Exclude this methods from the extraction
        $this->filterComposite->addFilter(
            'model',
            new MethodMatchFilter('getModel'),
            FilterComposite::CONDITION_AND
        );
        $this->filterComposite->addFilter(
            'hydrator',
            new MethodMatchFilter('getHydrator'),
            FilterComposite::CONDITION_AND
        );
        $this->filterComposite->addFilter(
            'inputFilter',
            new MethodMatchFilter('getInputFilter'),
            FilterComposite::CONDITION_AND
        );
    }
}
