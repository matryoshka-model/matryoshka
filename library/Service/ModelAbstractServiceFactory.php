<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Service;

use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\Criteria\PaginableCriteriaInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Model;
use Matryoshka\Model\ObservableModel;
use Matryoshka\Model\ResultSet\HydratingResultSet;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;

/**
 * Class ModelAbstractServiceFactory
 */
class ModelAbstractServiceFactory implements AbstractFactoryInterface
{
    use AbstractServiceFactoryTrait;

    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'matryoshka-models';

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
        if ($serviceLocator instanceof AbstractPluginManager && $serviceLocator->getServiceLocator()) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $this->getConfig($serviceLocator)[$requestedName];
        $dataGataway = $serviceLocator->get($config['datagateway']);
        /** @var $resultSetPrototype HydratingResultSet */
        $resultSetPrototype = $serviceLocator->get($config['resultset']);

        //Create a model instance
        $class = $this->modelClass;
        if (isset($config['type']) && is_string($config['type']) && !empty($config['type'])) {

            if (!is_subclass_of($config['type'], $class)) {
                throw new Exception\UnexpectedValueException('type must be a subclass of ' . $class);
            }

            $class = $config['type'];
        }

        /** @var $model Model */
        $model =  new $class($dataGataway, $resultSetPrototype);

        //Setup Hydrator
        $hydrator = null;
        if (isset($config['hydrator']) && is_string($config['hydrator']) && !empty($config['hydrator'])) {
            $hydrator = $this->getHydratorByName($serviceLocator, $config['hydrator']);
            $model->setHydrator($hydrator);
        }

        if ($hydrator && $resultSetPrototype instanceof HydratorAwareInterface) {
            $resultSetPrototype->setHydrator($hydrator);
        }

        //Setup InputFilter
        if (isset($config['input_filter']) && is_string($config['input_filter']) && !empty($config['input_filter'])) {
            $model->setInputFilter($this->getInputFilterByName($serviceLocator, $config['input_filter']));
        }

        //Setup Paginator
        if (isset($config['paginator_criteria'])
            && is_string($config['paginator_criteria'])
            && !empty($config['paginator_criteria'])
        ) {
            $model->setPaginatorCriteria($this->getPaginatorCriteriaByName(
                $serviceLocator,
                $config['paginator_criteria']
            ));
        }

        //Setup Object Prototype
        if (isset($config['object']) && is_string($config['object']) && !empty($config['object'])) {
            $resultSetPrototype->setObjectPrototype($this->getObjectByName($serviceLocator, $config['object']));
        }

        if (isset($config['listeners']) && is_array($config['listeners'])) {
            if ($model instanceof ObservableModel) {
                /** @var $model ObservableModel */
                $this->setListeners($serviceLocator, $config['listeners'], $model);
            } else {
                throw new ServiceNotCreatedException(sprintf(
                    'Instance of model must be a subclass of %s',
                    'Matryoshka\Model\ObservableModel'
                ));
            }
        }

        return $model;
    }

    /**
     * Retrieve object from config
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @return object
     */
    protected function getObjectByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        if ($serviceLocator->has('Matryoshka\Model\Object\ObjectManager')) {
            $serviceLocator = $serviceLocator->get('Matryoshka\Model\Object\ObjectManager');
        }

        return $serviceLocator->get($name);
    }

    /**
     * Retrieve PaginableCriteriaInterface object from config
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @return PaginableCriteriaInterface
     * @throws Exception\RuntimeException
     */
    protected function getPaginatorCriteriaByName(ServiceLocatorInterface $serviceLocator, $name)
    {
        /** @var $criteria CriteriaInterface */
        $criteria = $serviceLocator->get($name);
        if (!$criteria instanceof PaginableCriteriaInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($criteria) ? get_class($criteria) : gettype($criteria)),
                'Matryoshka\Model\Criteria\PaginableCriteriaInterface'
            ));
        }
        return $criteria;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $listeners
     * @param ObservableModel $model
     * @throws Exception\ServiceNotCreatedException
     */
    protected function setListeners(ServiceLocatorInterface $serviceLocator, array $listeners, ObservableModel $model)
    {
        $eventManager = $model->getEventManager();
        foreach ($listeners as $listener) {
            if ($serviceLocator->has($listener)
                && $serviceLocator->get($listener) instanceof ListenerAggregateInterface
            ) {
                $eventManager->attach($serviceLocator->get($listener));
            } else {
                throw new Exception\ServiceNotCreatedException(sprintf(
                    'Invalid service "%s" specified in "listeners" model configuration; must be an instance of "%s"',
                    $listener,
                    'Zend\EventManager\ListenerAggregateInterface'
                ));
            }
        }
    }
}
