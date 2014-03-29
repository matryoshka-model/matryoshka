<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 27/03/14
 * Time: 15.41
 */

namespace MatryoshkaTest\Model\Mock\Form\Fieldset;

use MatryoshkaTest\Model\Mock\AssertRoleCommunity;
use MatryoshkaTest\Model\Mock\AssertUser;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Stdlib\PriorityQueue;

class AssertUserFiedlset extends Fieldset
{
    const NAME = 'user';

    public function __construct()
    {
        parent::__construct(AssertUserFiedlset::NAME);
        $this->setObject(new AssertUser());

        $this->setUseAsBaseFieldset(true);
        $this->addFirstName();
        $this->addSurname();
        $this->addRoleCommunity();
    }

    /**
     * @return UserFiedlset
     */
    public function addSurname()
    {
        $elementTextSurName = new Element\Text('surname');
        $elementTextSurName->setLabel('Cognome');

        $this->add($elementTextSurName);
        return $this;
    }

    /**
     * @return UserFiedlset
     */
    public function addFirstName()
    {
        $elementTextSurName = new Element\Text('firstName');
        $elementTextSurName->setLabel('Nome');

        $this->add($elementTextSurName);
        return $this;
    }

    /**
     * @return UserFiedlset
     */
    public function addRoleCommunity()
    {
        $collectionRole = new Element\Collection('roles');
        $collectionRole->setCount(2)
            ->setAllowAdd(true)
            ->setShouldCreateTemplate(true)
            ->setTargetElement(new AssertRoleCommunityFiedlset());

        $this->add($collectionRole);
        return $this;
    }
}