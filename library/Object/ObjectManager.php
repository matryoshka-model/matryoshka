<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

use Matryoshka\Model\Exception;
use Matryoshka\Model\Object\Service\ObjectAbstractServiceFactory;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

/**
 * Class ObjectManager
 *
 * A dedicated service locator for managing domain model specific objects.
 */
class ObjectManager extends AbstractPluginManager
{
    /**
     * Share by default
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * {@inheritdoc}
     */
    public function validate($plugin)
    {
        if (!is_object($plugin)) {
            throw new Exception\InvalidPluginException(sprintf(
                'Type "%s" is invalid; must be an object',
                gettype($plugin)
            ));
        }
    }
}
