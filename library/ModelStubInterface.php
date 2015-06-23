<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Interface ModelStubInterface
 */
interface ModelStubInterface
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
     * Retrive data gateway
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
