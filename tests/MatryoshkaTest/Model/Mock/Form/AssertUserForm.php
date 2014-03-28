<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 27/03/14
 * Time: 17.01
 */

namespace MatryoshkaTest\Model\Mock\Form;

use MatryoshkaTest\Model\Mock\AssertUser;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AssertUserForm extends Form
{
    const NAME = 'form_user';

    public function __construct(AssertUser $user = null)
    {
        parent::__construct(AssertUserForm::NAME);

        $this->setAttribute('method', 'post');

        $this->addUserFieldset($user);
        $this->addSubmit();
    }

    /**
     * @param AssertUser $user
     * @return AssertUserForm
     */
    public function addUserFieldset(AssertUser $user = null)
    {
        $fieldsetUser = new Fieldset\AssertUserFiedlset($user);

        $this->add($fieldsetUser);
        return $this;
    }

    /**
     * @return AssertUserForm
     */
    public function addSubmit()
    {
        $elementSubmit = new Element\Submit('submit');
        $elementSubmit->setValue('Invio');

        $this->add($elementSubmit);
        return $this;
    }
}