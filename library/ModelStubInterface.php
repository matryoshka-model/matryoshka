<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Zend\InputFilter\InputFilterInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Interface ModelStubInterface
 *
 * Contract for model stub objects that allow access to persistence related services such as the datagateway,
 * the hydrator and the input filter (used for persistence purposes).
 * Matryoshka doesn't provide a persistence layer implementation itself,
 * but it can work with any third party component that acts as datagateway.
 * To accomplish this goal Matryoshka uses criteria interfaces that developer have to implement.
 * Classes implementing this interface are mainly intended to provide a set of services consumed by
 * the criteria implementations.
 */
interface ModelStubInterface extends ModelPrototypeInterface
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
     * @return HydratorInterface
     */
    public function getHydrator();

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter();
}
