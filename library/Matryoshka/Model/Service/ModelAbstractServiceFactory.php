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
use Matryoshka\Model\Exception;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Matryoshka\Model\ModelAwareInterface;

/**
 * Class ModelAbstractServiceFactory
 */
class ModelAbstractServiceFactory implements AbstractFactoryInterface
{

    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'model';

    /**
     * Default model class name
     *
     * @var string
     */
    protected $modelClass = '\Matryoshka\Model\Model';

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
        $dataGataway = $serviceLocator->get($config['datagateway']);
        $resultSetPrototype = $serviceLocator->get($config['resultset']);

        //Create a model instance
        $class = $this->modelClass;
        if (isset($config['type'])
            && is_string($config['type'])
            && !empty($config['type'])
        ) {

            if (!is_subclass_of($config['type'], $class)) {
                throw new Exception\UnexpectedValueException('type must be a subclass of ' . $class);
            }

            $class = $config['type'];
        }

        $model =  new $class($dataGataway, $resultSetPrototype);

        //Setup Hydrator
        $hydrator = null;
        if (isset($config['hydrator'])
            && is_string($config['hydrator'])
            && !empty($config['hydrator'])
            && $serviceLocator->has($config['hydrator'])
        ) {
            $hydrator = $serviceLocator->get($config['hydrator']);
            $model->setHydrator($hydrator);
        }

        if ($hydrator && $resultSetPrototype instanceof HydratorAwareInterface) {
            $resultSetPrototype->setHydrator($hydrator);
        }

        //Setup InputFilter
        $inputFilter = null;
        if (isset($config['input_filter'])
            && is_string($config['input_filter'])
            && !empty($config['input_filter'])
            && $serviceLocator->has($config['input_filter'])
        ) {
            $inputFilter = $serviceLocator->get($config['input_filter']);
            $model->setInputFilter($inputFilter);
        }

        //Setup Paginator
        if (isset($config['paginator_criteria'])
            && is_string($config['paginator_criteria'])
            && !empty($config['paginator_criteria'])
            && $serviceLocator->has($config['paginator_criteria'])
        ) {
            $paginatorCriteria = $serviceLocator->get($config['paginator_criteria']);
            $model->setPaginatorCriteria($paginatorCriteria);
        }

        //Setup Object Prototype
        if (isset($config['object'])
            && is_string($config['object'])
            && !empty($config['object'])
            && $serviceLocator->has($config['object'])
        ) {
            $object = $serviceLocator->get($config['object']);
            $resultSetPrototype->setObjectPrototype($object);
            if ($hydrator && $object instanceof HydratorAwareInterface) {
                $object->setHydrator($hydrator);
            }
            if ($inputFilter && $object instanceof InputFilterAwareInterface) {
                $object->setInputFilter($inputFilter);
            }
            if ($object instanceof ModelAwareInterface) {
                $object->setModel($model);
            }
        }

        return $model;
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
