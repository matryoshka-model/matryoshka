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
 *
 * Contract for model stub objects that allow access to persistence related services such as the datagateway,
 * the hydrator and the input filter (used for persistence purposes).
 * Matryoshka doesn't provide a persistence layer implementation itself,
 * but it can work with any third party implementation that acts as datagateway.
 * Regardless of the datagateway you choose, Matryoshka provides to clients the same set of handful API.
 * To accomplish this goal Matryoshka uses criteria interfaces that developer have to implement.
 * Classes implementing this interface are mainly intended to provide a set of services to criteria implementations.
 */
interface ModelStubInterface
{
    /**
     * Retrieve data gateway
     *
     * @return mixed
     */
    public function getDataGateway();

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
     * Retrieve object prototype
     *
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function getObjectPrototype();

    /**
     * Retrieve ResultSet prototype
     *
     * @return ResultSetInterface
     */
    public function getResultSetPrototype();
}
