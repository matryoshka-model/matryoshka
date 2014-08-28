<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterInterface;
use Matryoshka\Model\ResultSet\ResultSetInterface;

/**
 * Interface ModelInterface
 */
interface ModelInterface
{

    /**
     * Retrieve hydrator
     *
     * @param void
     * @return null|HydratorInterface
     */
    public function getHydrator();

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter();

    /**
     * Retrive data fateway
     *
     * @return mixed
     */
    public function getDataGateway();

    /**
     * Retrieve Object prototype
     *
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function getObjectPrototype();

    /**
     * Retrive ResultSet prototype
     *
     * @return ResultSetInterface
     */
    public function getResultSetPrototype();

}
