<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\Service;

use Interop\Container\ContainerInterface;
use Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Object\ActiveRecord\AbstractActiveRecord;
use Matryoshka\Model\Service\AbstractServiceFactoryTrait;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Hydrator\HydratorAwareInterface;

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
     * {@inheritdoc}
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
            && isset($config[$requestedName]['type'])
            && is_string($config[$requestedName]['type'])
            && !empty($config[$requestedName]['type'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container)[$requestedName];

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
            $object->setHydrator($this->getHydratorByName($container, $config['hydrator']));
        }

        //Setup InputFilter
        if ($object instanceof InputFilterAwareInterface
            && isset($config['input_filter'])
            && is_string($config['input_filter'])
            && !empty($config['input_filter'])
        ) {
            $object->setInputFilter($this->getInputFilterByName($container, $config['input_filter']));
        }

        //Setup ActiveRecord
        if ($object instanceof AbstractActiveRecord
            && isset($config['active_record_criteria'])
            && is_string($config['active_record_criteria'])
            && !empty($config['active_record_criteria'])
        ) {
            $object->setActiveRecordCriteriaPrototype($this->getActiveRecordCriteriaByName(
                $container,
                $config['active_record_criteria']
            ));
        }

        return $object;
    }

    /**
     * Retrieve PaginableCriteriaInterface object from config
     *
     * @param ContainerInterface $container
     * @param $name
     * @return mixed
     */
    protected function getActiveRecordCriteriaByName(ContainerInterface $container, $name)
    {
        $criteria = $container->get($name);
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
