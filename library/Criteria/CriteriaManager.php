<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Exception;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class CriteriaManager
 *
 * A dedicated service locator for managing criteria instances.
 */
class CriteriaManager extends AbstractPluginManager
{
    /**
     * Share by default
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Validate the plugin
     * Checks that the object loaded is an object.
     *
     * @param mixed $plugin
     * @throws Exception\InvalidPluginException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof CriteriaInterface) {
            throw new Exception\InvalidPluginException(sprintf(
                'Type "%s" is invalid; must be an instance of "%s"',
                gettype($plugin),
                CriteriaInterface::class
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
     * @return CriteriaInterface
     */
    public function get($name, $options = [], $usePeeringServiceManagers = false)
    {
        return parent::get($name, $options, $usePeeringServiceManagers);
    }
}
