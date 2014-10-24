<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

use Matryoshka\Model\Exception;
use Matryoshka\Model\Object\Service\ObjectAbstractServiceFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class ObjectManager
 */
class ObjectManager extends AbstractPluginManager
{
    /**
     * Share by default
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Constructor
     * Add a default initializer to ensure the plugin is valid after instance
     * creation.
     * @param  null|ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->addAbstractFactory(new ObjectAbstractServiceFactory());
    }

    /**
     * Validate the plugin
     * Checks that the object loaded is an object.
     * @param mixed $plugin
     * @throws Exception\InvalidPluginException
     */
    public function validatePlugin($plugin)
    {
        if (!is_object($plugin)) {
            throw new Exception\InvalidPluginException(sprintf(
                'Type %s is invalid; must implement be an object',
                gettype($plugin)
            ));
        }
    }
}
