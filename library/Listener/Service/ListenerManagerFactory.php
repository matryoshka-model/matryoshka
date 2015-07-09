<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Listener\Service;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Matryoshka\Model\Listener\ListenerManager;

/**
 * Class ListenerManagerFactory
 */
class ListenerManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $objectConfig = [];
        if (isset($config['matryoshka']) && isset($config['matryoshka']['listener_manager'])) {
            $objectConfig = $config['matryoshka']['listener_manager'];
        }
        return new ListenerManager(new Config($objectConfig));
    }
}
