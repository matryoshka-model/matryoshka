<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 27/03/14
 * Time: 15.41
 */

namespace MatryoshkaTest\Model\Mock\Form\Fieldset;

use MatryoshkaTest\Model\Mock\AssertRoleCommunity;

use Zend\Form\Element;
use Zend\Form\Fieldset;

class AssertRoleCommunityFiedlset extends Fieldset
{
    const NAME = 'roleCommunity';

    public function __construct()
    {
        parent::__construct(AssertRoleCommunityFiedlset::NAME);

        $this->setObject(new AssertRoleCommunity());
        $this->addName();
    }

    /**
     * @return UserFiedlset
     */
    public function addName()
    {
        $elementTextSurName = new Element\Text('name');
        $elementTextSurName->setLabel('Name');

        $this->add($elementTextSurName);
        return $this;
    }
}