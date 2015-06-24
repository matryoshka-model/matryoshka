<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
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
     * Constructor
     * Add a default initializer to ensure the plugin is valid after instance
     * creation.
     * @param  null|ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->addAbstractFactory(new ModelAbstractServiceFactory());
    }


    /**
     * Validate the plugin
     * Checks that the model loaded is an instance of ModelInterface.
     * @param mixed $plugin
     * @throws Exception\InvalidPluginException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof ModelInterface) {
            throw new Exception\InvalidPluginException(sprintf(
                'Model of type %s is invalid; must implement %s\ModelInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
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
     * @return ModelInterface
     */
    public function get($name, $options = [], $usePeeringServiceManagers = false)
    {
        return parent::get($name, $options, $usePeeringServiceManagers);
    }
}
