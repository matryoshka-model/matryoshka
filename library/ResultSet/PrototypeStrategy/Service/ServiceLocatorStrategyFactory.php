<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet\PrototypeStrategy\Service;

use Matryoshka\Model\Object\PrototypeStrategy\Service\ServiceLocatorStrategyFactory as ObjectServiceLocatorStrategyFactory;

/**
 * Class ServiceLocatatorStrategyFactory
 *
 * @deprecated
 * NOTE: refactored to do not introduce breaking changes
 */
class ServiceLocatorStrategyFactory extends ObjectServiceLocatorStrategyFactory
{
    /**
     * @var string
     */
    protected $configKey = 'matryoshka-resultset-servicelocatorstrategy';
}
