<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Mock;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Stdlib\hydrator\HydratorInterface;

/**
 * Class AssertRoleCommunity
 */
class AssertRoleCommunity implements HydratorAwareInterface, InputFilterAwareInterface
{
    use HydratorAwareTrait;
    use InputFilterAwareTrait;

    /**
     * @var
     */
    protected $_id;

    /**
     * @var String
     */
    protected $name;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->setHydrator(new ClassMethods(false));
        }
        return $this->hydrator;
    }

    /**
     * @return InputFilterInterface
     */

    public function getInputFilter()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name' => 'name',
                'required' => true,
            ]
        );

        return $inputFilter;
    }
}
