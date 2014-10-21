<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Mock\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class AssertUserForm extends Form
{
    const NAME = 'form_user';

    public function __construct()
    {
        parent::__construct(AssertUserForm::NAME);

        $this->setAttribute('method', 'post');

        $this->addUserFieldset();
        $this->addSubmit();
    }

    /**
     * @return $this
     */
    public function addUserFieldset()
    {
        $fieldsetUser = new Fieldset\AssertUserFiedlset();

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
