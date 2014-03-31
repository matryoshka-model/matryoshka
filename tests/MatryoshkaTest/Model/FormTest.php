<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model;

use MatryoshkaTest\Model\Mock\Form\AssertUserForm;
use MatryoshkaTest\Model\Mock\AssertUser;
use MatryoshkaTest\Model\Mock\AssertRoleCommunity;

class FormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AssertRoleCommunity
     */
    protected $roleCommunity2;

    /**
     * @var AssertRoleCommunity
     */
    protected $roleCommunity1;

    /**
     * @var AssertUser
     */
    protected $user;

    /**
     * @var AssertUserForm
     */
    protected $form;

    public function setUp()
    {
        $this->roleCommunity1 = new AssertRoleCommunity();
        $this->roleCommunity1->setName('matrilska');

        $this->roleCommunity2 = new AssertRoleCommunity();
        $this->roleCommunity2->setName('matrioska 2');


        $this->user = new AssertUser();
        $this->user->setFirstName('avisalli');
        $this->user->setSurname('dmlab');
        $this->user->setAge(3);
        $this->user->setRoles(array( $this->roleCommunity1,  $this->roleCommunity2));

        $this->form = new AssertUserForm();
        $this->form->bind($this->user);
        $this->form->prepare();

    }

   public function testUserAttributeSurname()
   {
       $this->assertSame($this->form->get('user')->get('surname')->getValue(),  $this->user->getSurname());
   }

    public function testUserAttributeFirstname()
    {
        $this->assertSame($this->form->get('user')->get('firstName')->getValue(),  $this->user->getFirstName());
    }

    public function testCollectionAttribute()
    {
        $this->assertSame($this->form->get('user')->get('roles')->get('0')->get('name')->getValue(),  $this->roleCommunity1->getName());
        $this->assertSame($this->form->get('user')->get('roles')->get('1')->get('name')->getValue(),  $this->roleCommunity2->getName());
    }

    public function testValidationForm()
    {
        $user = new AssertUser();
        $user->setFirstName('avisalli');
        $user->setSurname('bigolo');
        $user->setAge(3);

        $form = new AssertUserForm();
        $form->bind($user);

        // TODO with parameter in setdata
        $form->setData(array());

        $this->assertFalse($form->isValid(), 'fdfff');
    }
}
