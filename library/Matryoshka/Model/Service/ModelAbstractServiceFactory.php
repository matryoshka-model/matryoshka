<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Matryoshka\Model\Model;
use Matryoshka\Model\Exception;
use Zend\ServiceManager\AbstractPluginManager;

class ModelAbstractServiceFactory implements AbstractFactoryInterface
{

    /**
     * @var string
     */
    protected $configKey = 'model';

    /**
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @return boolean
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $this->getConfig($serviceLocator);
        if (empty($config)) {
            return false;
        }

        return (
            isset($config[$requestedName])
            && is_array($config[$requestedName])
            && !empty($config[$requestedName])
            && isset($config[$requestedName]['datagateway'])
            && is_string($config[$requestedName]['datagateway'])
            && !empty($config[$requestedName]['datagateway'])
            && $serviceLocator->has($config[$requestedName]['datagateway'])
            && isset($config[$requestedName]['resultset'])
            && is_string($config[$requestedName]['resultset'])
            && !empty($config[$requestedName]['resultset'])
            && $serviceLocator->has($config[$requestedName]['resultset'])
        );
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @return Model
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $this->getConfig($serviceLocator)[$requestedName];

        $dataGataway = $serviceLocator->get($config['datagateway']);
        $resultSetPrototype   = $serviceLocator->get($config['resultset']);

        if (isset($config['object'])
            && is_string($config['object'])
            && !empty($config['object'])
            && $serviceLocator->has($config['object'])) {
            $resultSetPrototype->setObjectPrototype($serviceLocator->get($config['object']));
        }

        $hydrator = null;
        if (isset($config['hydrator'])
            && is_string($config['hydrator'])
            && !empty($config['hydrator'])
            && $serviceLocator->has($config['hydrator'])) {
            $hydrator = $serviceLocator->get($config['hydrator']);
        }

        $class = '\Matryoshka\Model\Model';
        if (isset($config['type'])
            && is_string($config['type'])
            && !empty($config['type'])) {

            if (!is_subclass_of($config['type'], $class)) {
                throw new Exception\UnexpectedValueException('type must be a subclass of ' . $class);
            }

            $class = $config['type'];
        }

        return new $class($dataGataway, $resultSetPrototype, $hydrator);
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
            $this->config = array();
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey])
        || !is_array($config[$this->configKey])
        ) {
            $this->config = array();
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }
}