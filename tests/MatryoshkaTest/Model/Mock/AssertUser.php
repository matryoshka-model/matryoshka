<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 27/03/14
 * Time: 15.30
 */

namespace MatryoshkaTest\Model\Mock;


class AssertUser
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
    protected $roleCommunity;

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
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return String
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param \MatryoshkaTest\Model\Mock\AssertRoleCommunity $roleCommunity
     */
    public function setRoleCommunity(AssertRoleCommunity $roleCommunity)
    {
        $this->roleCommunity = $roleCommunity;
    }

    /**
     * @return \MatryoshkaTest\Model\Mock\AssertRoleCommunity
     */
    public function getRoleCommunity()
    {
        return $this->roleCommunity;
    }

    /**
     * @param String $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return String
     */
    public function getSurname()
    {
        return $this->surname;
    }


} 