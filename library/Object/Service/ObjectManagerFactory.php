<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\Service;

use Matryoshka\Model\Object\ObjectManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ObjectManagerFactory
 */
class ObjectManagerFactory implements FactoryInterface
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
        if (isset($config['matryoshka']) && isset($config['matryoshka']['object_manager'])) {
            $objectConfig = $config['matryoshka']['object_manager'];
        }
        return new ObjectManager(new Config($objectConfig));
    }
}
