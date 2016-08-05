<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Service\ModelAbstractServiceFactory;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

/**
 * Class ModelManager
 *
 * A dedicated service locator for your model service classes.
 */
class ModelManager extends AbstractPluginManager
{
    /**
     * Share by default
     * @var bool
     */
    protected $shareByDefault = true;

    /**
     * {@inheritdoc}
     */
    public function validate($plugin)
    {
        if (!$plugin instanceof ModelInterface) {
            throw new Exception\InvalidPluginException(sprintf(
                'Model of type %s is invalid; must implement "%s"',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                ModelInterface::class
            ));
        }
    }
}
