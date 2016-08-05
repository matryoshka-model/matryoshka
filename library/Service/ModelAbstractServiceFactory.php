<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Service;

use Interop\Container\ContainerInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyAwareInterface;
use Matryoshka\Model\ObservableModel;
use Matryoshka\Model\ResultSet\BufferedResultSet;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Hydrator\HydratorAwareInterface;

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
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $this->getConfig($container);
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
            && $container->has($config[$requestedName]['datagateway'])
            && isset($config[$requestedName]['resultset'])
            && is_string($config[$requestedName]['resultset'])
            && !empty($config[$requestedName]['resultset'])
            && $container->has($config[$requestedName]['resultset'])
        );
    }


    /**
     * Create service with name
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return \Matryoshka\Model\Model|ObservableModel
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container)[$requestedName];
        $dataGataway = $container->get($config['datagateway']);
        /* @var $resultSetPrototype \Matryoshka\Model\ResultSet\ResultSetInterface */
        $resultSetPrototype = $container->get($config['resultset']);

        if (isset($config['buffered_resultset']) && $config['buffered_resultset']) {
            /* @var $resultSetPrototype \Matryoshka\Model\ResultSet\AbstractResultSet */
            $resultSetPrototype = new BufferedResultSet($resultSetPrototype);
        }

        //Create a model instance
        $class = $this->modelClass;
        if (!empty($config['type']) && is_string($config['type'])) {
            if (!is_subclass_of($config['type'], $class)) {
                throw new Exception\UnexpectedValueException(sprintf(
                    '"type" in model configuration must be a subclass of "%s": "%s" given',
                    $class,
                    $config['type']
                ));
            }

            $class = $config['type'];
        }

        /* @var $model \Matryoshka\Model\Model */
        $model =  new $class($dataGataway, $resultSetPrototype);

        //Setup Hydrator
        $hydrator = null;
        if (!empty($config['hydrator']) && is_string($config['hydrator'])) {
            $hydrator = $this->getHydratorByName($container, $config['hydrator']);
            $model->setHydrator($hydrator);
        }

        if ($hydrator && $resultSetPrototype instanceof HydratorAwareInterface) {
            $resultSetPrototype->setHydrator($hydrator);
        }

        //Setup InputFilter
        if (!empty($config['input_filter']) && is_string($config['input_filter'])) {
            $model->setInputFilter($this->getInputFilterByName($container, $config['input_filter']));
        }

        //Setup Paginator
        if (!empty($config['paginator_criteria']) && is_string($config['paginator_criteria'])) {
            $model->setPaginatorCriteria($this->getPaginatorCriteriaByName(
                $container,
                $config['paginator_criteria']
            ));
        }

        //Setup Object Prototype
        if (!empty($config['object']) && is_string($config['object'])) {
            $resultSetPrototype->setObjectPrototype($this->getObjectByName($container, $config['object']));
        }

        //Setup Prototype strategy
        if (!empty($config['prototype_strategy']) && is_string($config['prototype_strategy'])) {
            if ($resultSetPrototype instanceof PrototypeStrategyAwareInterface) {
                $resultSetPrototype->setPrototypeStrategy(
                    $this->getPrototypeStrategyByName($container, $config['prototype_strategy'])
                );
            }
        }

        //Setup listeners
        if (!empty($config['listeners']) && is_array($config['listeners'])) {
            if ($model instanceof ObservableModel) {
                /** @var $model ObservableModel */
                $this->injectListeners($container, $config['listeners'], $model);
            } else {
                throw new Exception\ServiceNotCreatedException(sprintf(
                    'Instance of model must be a subclass of "%s" in order to attach listeners',
                    ObservableModel::class
                ));
            }
        }

        return $model;
    }

    /**
     * @param ContainerInterface $container
     * @param array $listeners
     * @param ObservableModel $model
     * @throws Exception\ServiceNotCreatedException
     */
    protected function injectListeners(
        ContainerInterface $container,
        array $listeners,
        ObservableModel $model
    ) {
        $eventManager = $model->getEventManager();
        if ($container->has('Matryoshka\Model\Listener\ListenerManager')) {
            $container = $container->get('Matryoshka\Model\Listener\ListenerManager');
        }
        foreach ($listeners as $listener) {
            $listenerAggregate = $container->get($listener);
            if ($listenerAggregate instanceof ListenerAggregateInterface) {
                $listenerAggregate->attach($eventManager);
            } else {
                throw new Exception\ServiceNotCreatedException(sprintf(
                    'Invalid service "%s" specified in "listeners" model configuration; must be an instance of "%s"',
                    $listener,
                    ListenerAggregateInterface::class
                ));
            }
        }
    }
}
