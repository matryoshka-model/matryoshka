<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Exception;

use Zend\ServiceManager\Exception\ServiceNotCreatedException as ZendServiceNotCreatedException;

/**
 * Class ServiceNotCreatedException
 */
class ServiceNotCreatedException extends ZendServiceNotCreatedException implements ExceptionInterface
{
}
