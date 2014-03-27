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
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AssertRoleCommunityFiedlset extends Fieldset implements InputFilterProviderInterface
{
    const NAME = 'roleCommunity';

    public function __construct(AssertRoleCommunity $roleCommunity = null)
    {
        parent::__construct(UserFiedlset::NAME);

        $this->setHydrator(new ClassMethodsHydrator(false))
             ->injectionEntity($roleCommunity);

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

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
            )
        );
    }
}