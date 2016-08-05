<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\Service;

use Interop\Container\ContainerInterface;
use Matryoshka\Model\Object\ObjectManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ObjectManagerFactory
 */
class ObjectManagerFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $objectConfig = [];
        if (isset($config['matryoshka']) && isset($config['matryoshka']['object_manager'])) {
            $objectConfig = $config['matryoshka']['object_manager'];
        }

        $objectManager = new ObjectManager($container, $objectConfig);
        $objectManager->addAbstractFactory(new ObjectAbstractServiceFactory());

        return $objectManager;
    }
}
