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
    protected $roleCommunity2;

    protected $roleCommunity1;

    protected $user;

    public function setUp()
    {
        //$this->form = new AssertUserForm();
        $this->roleCommunity1 = new AssertRoleCommunity();
        $this->roleCommunity1->setName('mangionissimo');

        $this->roleCommunity2 = new AssertRoleCommunity();
        $this->roleCommunity2->setName('mangionissimo 22222222222222');


        $this->user = new AssertUser();
        $this->user->setFirstName('antonio');
        $this->user->setSurname('dmlab');
        $this->user->setAge(3);
        $this->user->setRoles(array( $this->roleCommunity1,  $this->roleCommunity2));
    }

   public function testMock()
   {
       $form = new AssertUserForm();

       var_dump($form->get('user')->get('surname')->getValue());
       die();
   }
}
