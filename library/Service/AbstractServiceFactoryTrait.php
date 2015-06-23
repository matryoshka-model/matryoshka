<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Service;

use Matryoshka\Model\Exception;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Trait AbstractServiceFactoryTrait
 */
trait AbstractServiceFactoryTrait
{
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
        if (!isset($config[$this->configKey]) || !is_array($config[$this->configKey])) {
            $this->config = [];
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }

    /**
     * Retrieve HydratorInterface object from config
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @return HydratorInterface
     * @throws Exception\RuntimeException
     */
    protected function getHydratorByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        if ($serviceLocator->has('HydratorManager')) {
            $serviceLocator = $serviceLocator->get('HydratorManager');
        }

        $obj = $serviceLocator->get($name);
        if (!$obj instanceof HydratorInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($obj) ? get_class($obj) : gettype($obj)),
                'Zend\Stdlib\Hydrator\HydratorInterface'
            ));
        }
        return $obj;
    }

    /**
     * Retrieve InputFilterInterface object from config
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @return InputFilterInterface
     * @throws Exception\RuntimeException
     */
    protected function getInputFilterByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        if ($serviceLocator->has('InputFilterManager')) {
            $serviceLocator = $serviceLocator->get('InputFilterManager');
        }

        $obj = $serviceLocator->get($name);
        if (!$obj instanceof InputFilterInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($obj) ? get_class($obj) : gettype($obj)),
                'Zend\InputFilter\InputFilterInterface'
            ));
        }
        return $obj;
    }
}
