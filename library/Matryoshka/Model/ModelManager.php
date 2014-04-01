<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Matryoshka\Model\Service\ModelAbstractServiceFactory;

/**
 * Class ModelManager
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
}
