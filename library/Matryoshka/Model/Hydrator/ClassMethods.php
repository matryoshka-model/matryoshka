<?php
namespace Matryoshka\Model\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as ZendClassMethods;

class ClassMethods extends ZendClassMethods
{
    public function __construct()
    {
        parent::__construct();

        $this->filterComposite->addFilter('model',  new MethodMatchFilter('getModel'), FilterComposite::CONDITION_AND);
        $this->filterComposite->addFilter('hydrator',  new MethodMatchFilter('getHydrator'), FilterComposite::CONDITION_AND);
        $this->filterComposite->addFilter('inputFilter',  new MethodMatchFilter('getInputFilter'), FilterComposite::CONDITION_AND);
    }
} 