<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\Service;

use Matryoshka\Model\Model;
use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelAwareInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Matryoshka\Model\Object\AbstractObject;
use Zend\InputFilter\InputFilterAwareInterface;
use Matryoshka\Model\Object\ActiveRecordInterface;
use Matryoshka\Model\Object\AbstractActiveRecord;

/**
 * Class ObjectAbstractServiceFactory
 */
class ObjectAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'object';


    /**
     * Config
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param string                  $requestedName
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
            && isset($config[$requestedName]['type'])
            && is_string($config[$requestedName]['type'])
            && !empty($config[$requestedName]['type'])
        );
    }


    /**
     * Create service with name
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     * @return mixed
     * @throws \Matryoshka\Model\Exception\UnexpectedValueException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $this->getConfig($serviceLocator)[$requestedName];

        //Create an object instance
        $class = $config['type'];

        //FIXME: check if class exists
        $object =  new $class;

        //Setup Hydrator
        $hydrator = null;
        if ($object instanceof HydratorAwareInterface
            && isset($config['hydrator'])
            && is_string($config['hydrator'])
            && !empty($config['hydrator'])
        ) {
            $object->setHydrator($this->getHydratorByName($serviceLocator, $config['hydrator']));
        }

        //Setup InputFilter
        if ($object instanceof InputFilterAwareInterface
            && isset($config['input_filter'])
            && is_string($config['input_filter'])
            && !empty($config['input_filter'])
        ) {
            $object->setInputFilter($this->getInputFilterByName($serviceLocator, $config['input_filter']));
        }

        //Setup Model
        if ($object instanceof ModelAwareInterface
            && isset($config['model'])
            && is_string($config['model'])
            && !empty($config['model'])
        ) {
            $object->setModel($this->getModelByName($serviceLocator, $config['model']));
        }

        //Setup ActiveRecord
        if ($object instanceof AbstractActiveRecord
            && isset($config['active_record_criteria'])
            && is_string($config['active_record_criteria'])
            && !empty($config['active_record_criteria'])
        ) {
            if (!$serviceLocator->has($config['active_record_criteria'])) {
                throw Exception\InvalidArgumentException('active_record_criteria not found');
            }
            $object->setActiveRecordCriteriaPrototype($serviceLocator->get($config['active_record_criteria']));
        }

        return $object;
    }

    protected function getHydratorByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        if ($serviceLocator->has('HydratorManager')) {
            $serviceLocator = $serviceLocator->get('HydratorManager');
        }

        if ($serviceLocator->has($name)) {
            return $serviceLocator->get($name);
        }

        return null; //FIXME: throw exception
    }

    protected function getInputFilterByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        if ($serviceLocator->has('InputFilterManager')) {
            $serviceLocator = $serviceLocator->get('InputFilterManager');
        }

        if ($serviceLocator->has($name)) {
            return $serviceLocator->get($name);
        }

        return null; //FIXME: throw exception
    }


    protected function getModelByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        if ($serviceLocator->has('Matryoshka\Model\ModelManager')) {
            $serviceLocator = $serviceLocator->get('Matryoshka\Model\ModelManager');
        }

        if ($serviceLocator->has($name)) {
            return $serviceLocator->get($name);
        }

        return null; //FIXME: throw exception
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
