<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\Service;

use Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Object\ActiveRecord\AbstractActiveRecord;
use Matryoshka\Model\Service\AbstractServiceFactoryTrait;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;

/**
 * Class ObjectAbstractServiceFactory
 */
class ObjectAbstractServiceFactory implements AbstractFactoryInterface
{
    use AbstractServiceFactoryTrait;

    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'matryoshka-objects';

    /**
     * Config
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     * @return boolean
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($serviceLocator instanceof AbstractPluginManager && $serviceLocator->getServiceLocator()) {
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
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($serviceLocator instanceof AbstractPluginManager && $serviceLocator->getServiceLocator()) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $this->getConfig($serviceLocator)[$requestedName];

        //Create an object instance
        $class = $config['type'];
        if (!class_exists($class)) {
            throw new Exception\RuntimeException(sprintf(
                'ObjectAbstractServiceFactory expects the "type" to be a valid class; received "%s"',
                $class
            ));
        }
        $object = new $class;

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

        //Setup ActiveRecord
        if ($object instanceof AbstractActiveRecord
            && isset($config['active_record_criteria'])
            && is_string($config['active_record_criteria'])
            && !empty($config['active_record_criteria'])
        ) {
            $object->setActiveRecordCriteriaPrototype($this->getActiveRecordCriteriaByName(
                $serviceLocator,
                $config['active_record_criteria']
            ));
        }

        return $object;
    }

    /**
     * Retrieve PaginableCriteriaInterface object from config
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @return AbstractCriteria
     * @throws Exception\ServiceNotCreatedException
     */
    protected function getActiveRecordCriteriaByName($serviceLocator, $name)
    {
        /** @var $criteria CriteriaInterface */
        $criteria = $serviceLocator->get($name);
        if (!$criteria instanceof AbstractCriteria) {
            throw new Exception\ServiceNotCreatedException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($criteria) ? get_class($criteria) : gettype($criteria)),
                AbstractCriteria::class
            ));
        }
        return $criteria;
    }
}
