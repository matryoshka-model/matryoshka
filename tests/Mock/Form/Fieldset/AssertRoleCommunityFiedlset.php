<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Mock\Form\Fieldset;

use MatryoshkaTest\Model\Mock\AssertRoleCommunity;
use Zend\Form\Element;
use Zend\Form\Fieldset;

/**
 * Class AssertRoleCommunityFiedlset
 */
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
