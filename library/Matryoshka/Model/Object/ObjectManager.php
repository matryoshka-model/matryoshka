<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ConfigInterface;
use Matryoshka\Model\ModelAwareInterface;

/**
 * Class ObjectManager
 */
class ObjectManager extends ServiceManager
{
    /**
     * Share by default
     * @var bool
     */
    protected $shareByDefault = false;

}
