<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Listener\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Matryoshka\Model\Listener\ListenerManager;

/**
 * Class ListenerManagerFactory
 */
class ListenerManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ListenerManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $listenerConfig = [];
        if (isset($config['matryoshka']) && isset($config['matryoshka']['listener_manager'])) {
            $listenerConfig = $config['matryoshka']['listener_manager'];
        }

        return new ListenerManager($container, $listenerConfig);
    }
}
