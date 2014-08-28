<?php
/**
 * Created by visa
 * Date:  28/08/14 16.44
 * Class: ObjectFactory.php
 */

namespace Matryoshka\Model\Object;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class ObjectFactory implements FactoryInterface
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
        $objectConfig =  isset($config['object_manager']) ? $config['object_manager'] : array();
        return new ServiceManager(new Config($objectConfig));
    }

} 