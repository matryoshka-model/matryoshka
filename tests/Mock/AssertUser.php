<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Mock;

use Matryoshka\Model\Hydrator\ClassMethods;
use Matryoshka\Model\Object\AbstractObject;
use Zend\InputFilter\InputFilter;
use Zend\Hydrator\HydratorInterface;

/**
 * Class AssertUser
 */
class AssertUser extends AbstractObject
{
    /**
     * @var
     */
    protected $_id;

    /**
     * @var String
     */
    protected $firstName;

    /**
     * @var String
     */
    protected $surname;

    /**
     * @var int
     */
    protected $age;

    /**
     * @var RoleCommunity
     */
    protected $roles;

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
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param String $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return String
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $surname
     * @return $this
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return String
     */
    public function getSurname()
    {
        return $this->surname;
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
                'name' => 'firstName',
                'required' => true,
            ]
        );


        $inputFilter->add(
            [
                'name' => 'surname',
                'required' => true,
            ]
        );

        return $inputFilter;
    }
}
