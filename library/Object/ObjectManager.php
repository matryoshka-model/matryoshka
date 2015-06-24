<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
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
     * Constructor
     * Add a default initializer to ensure the plugin is valid after instance creation.
     *
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
     *
     * @param mixed $plugin
     * @throws Exception\InvalidPluginException
     */
    public function validatePlugin($plugin)
    {
        if (!is_object($plugin)) {
            throw new Exception\InvalidPluginException(sprintf(
                'Type %s is invalid; must be an object',
                gettype($plugin)
            ));
        }
    }

    /**
     * Override: do not use peering service managers
     *
     * @param  string|array $name
     * @param  bool         $checkAbstractFactories
     * @param  bool         $usePeeringServiceManagers
     * @return bool
     */
    public function has($name, $checkAbstractFactories = true, $usePeeringServiceManagers = false)
    {
        return parent::has($name, $checkAbstractFactories, $usePeeringServiceManagers);
    }

    /**
     * Override: do not use peering service managers
     *
     * @param  string $name
     * @param  array $options
     * @param  bool $usePeeringServiceManagers
     * @return object
     */
    public function get($name, $options = [], $usePeeringServiceManagers = false)
    {
        return parent::get($name, $options, $usePeeringServiceManagers);
    }
}
