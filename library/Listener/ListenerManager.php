<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Listener;

use Matryoshka\Model\Exception;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class ListenerManager
 *
 * A dedicated service locator for ObservableModel listeners.
 */
class ListenerManager extends AbstractPluginManager
{
    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * Validate the plugin
     * Checks that the object loaded is an instance of ListenerAggregateInterface
     *
     * @param mixed $plugin
     * @throws Exception\InvalidPluginException
     */
    public function validate($plugin)
    {
        if (!$plugin instanceof ListenerAggregateInterface) {
            throw new Exception\InvalidPluginException(sprintf(
                'Listener of type "%s" is invalid; must implement "%s"',
                is_object($plugin) ? get_class($plugin) : gettype($plugin),
                ListenerAggregateInterface::class
            ));
        }
    }
}
