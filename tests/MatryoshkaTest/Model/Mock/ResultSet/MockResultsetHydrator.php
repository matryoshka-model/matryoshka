<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 26/03/14
 * Time: 16.14
 */

namespace MatryoshkaTest\Model\Mock\ResultSet;

use Matryoshka\Model\ResultSet\ResultSet;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MockResultsetHydrator extends ResultSet implements HydratorAwareInterface {

    protected $hydrator = null;

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ClassMethods();
        }
        return $this->hydrator;
    }
}
