<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
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
        $this->roleCommunity1->setName('Matryoshka');

        $this->roleCommunity2 = new AssertRoleCommunity();
        $this->roleCommunity2->setName('Matryoshka 2');

        $this->user = new AssertUser();
        $this->user->setFirstName('avisalli');
        $this->user->setSurname('ripaclub');
        $this->user->setAge(3);
        $this->user->setRoles([ $this->roleCommunity1,  $this->roleCommunity2]);

        $this->form = new AssertUserForm();
        $this->form->bind($this->user);


    }

   public function testBoundObjectExtraction()
   {
       //test form is populated with bound object values.
       $this->form->prepare();
       $this->assertSame($this->form->get('user')->get('surname')->getValue(),  $this->user->getSurname());
       $this->assertSame($this->form->get('user')->get('firstName')->getValue(),  $this->user->getFirstName());
       $this->assertSame($this->form->get('user')->get('roles')->get('0')->get('name')->getValue(),  $this->roleCommunity1->getName());
       $this->assertSame($this->form->get('user')->get('roles')->get('1')->get('name')->getValue(),  $this->roleCommunity2->getName());
   }

    public function testBoundObjectValidation()
    {
        $form = $this->form;
        $this->assertTrue($form->isValid());

        $form->setData([
            'user' => [
                'surname' => 'foo',
                'firstName' => 'baz',
                'roles' => [
                    ['name' => 'role1'],
                    ['name' => 'role2'],
                ],
            ]
        ]);
        $this->assertTrue($form->isValid());

        $form->setData([
            'user' => [
                'surname' => 'baz',

            ]
        ]);
        $this->assertFalse($form->isValid());

    }

    public function testBoundObjectHydratation()
    {
        $form = $this->form;

        $form->setData([
            'user' => [
                'surname' => 'foo',
                'firstName' => 'baz',
                'roles' => [
                    ['name' => 'role1'],
                    ['name' => 'role2'],
                ],
            ]
        ]);

        //if form is the object will be bound
        $this->assertTrue($form->isValid());

        $this->assertSame('foo', $this->user->getSurname());
        $this->assertSame('baz', $this->user->getFirstName());
        $this->assertSame('role1', $this->user->getRoles()[0]->getName());
        $this->assertSame('role2', $this->user->getRoles()[1]->getName());
    }
}
