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

    public function __construct(AssertRoleCommunity $roleCommunity = null)
    {
        parent::__construct(AssertRoleCommunityFiedlset::NAME);

        $this->injectionEntity($roleCommunity);

        $this->addName();
    }

    /**
     * @return UserFiedlset
     */
    public function addName()
    {
        $elementTextSurName = new Element\Text('name');
        $elementTextSurName->setLabel('Name');

        $this->addSurName($elementTextSurName);
        return $this;
    }

    /**
     * @param null AssertRoleCommunity
     * @return $this
     */
    protected function injectionEntity($entity = null)
    {
        if($entity){
            $this->setObject(new AssertRoleCommunity());
        }
        else{
            $this->setObject($entity);
            // TODO populate
        }
        return $this;
    }
}