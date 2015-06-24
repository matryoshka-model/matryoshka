<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\PrototypeStrategy\Service;

use Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServiceLocatatorStrategyFactory
 */
class ServiceLocatorStrategyFactory implements FactoryInterface
{
    /**
     * @var string
     */
    protected $configKey = 'matryoshka-object-servicelocatorstrategy';

    /**
     * Config
     * @var array
     */
    protected $config;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);

        if (isset($config['service_locator'])) {
            $objectServiceLocator = $serviceLocator->get($config['service_locator']);
        } else {
            $objectServiceLocator = $serviceLocator->has('Matryoshka\Model\Object\ObjectManager') ?
                $serviceLocator->get('Matryoshka\Model\Object\ObjectManager')
                : $serviceLocator;
        }

        $strategy = new ServiceLocatorStrategy($objectServiceLocator);

        if (isset($config['type_field'])) {
            $strategy->setTypeField($config['type_field']);
        }

        if (isset($config['validate_object'])) {
            $strategy->setValidateObject($config['validate_object']);
        }

        if (isset($config['clone_object'])) {
            $strategy->setCloneObject($config['clone_object']);
        }

        return $strategy;
    }

    /**
     * Get model configuration, if any
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$serviceLocator->has('Config')) {
            $this->config = [];
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey])
            || !is_array($config[$this->configKey])
        ) {
            $this->config = [];
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }
}
