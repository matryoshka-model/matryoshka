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
use Matryoshka\Model\Criteria\PaginableCriteriaInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Object\PrototypeStrategy\PrototypeStrategyInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Trait AbstractServiceFactoryTrait
 */
trait AbstractServiceFactoryTrait
{
    /**
     * @param ContainerInterface $container
     * @return array
     */
    protected function getConfig(ContainerInterface $container)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$container->has('Config')) {
            return $this->config = [];
        }

        $config = $container->get('Config');
        if (!isset($config[$this->configKey]) || !is_array($config[$this->configKey])) {
            $this->config = [];
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }

    /**
     * Retrieve object from service locator
     *
     * @param ContainerInterface $serviceLocator
     * @param $name
     * @return object
     */
    protected function getObjectByName(ContainerInterface $container, $name)
    {
        if ($container->has('Matryoshka\Model\Object\ObjectManager')) {
            $serviceLocator = $container->get('Matryoshka\Model\Object\ObjectManager');
        }

        return $container->get($name);
    }

    /**
     * Retrieve PaginableCriteriaInterface object from service locator
     *
     * @param ContainerInterface $serviceLocator
     * @param $name
     * @return PaginableCriteriaInterface
     * @throws Exception\ServiceNotCreatedException
     */
    protected function getPaginatorCriteriaByName(ContainerInterface $serviceLocator, $name)
    {
        $criteria = $serviceLocator->get($name);
        if (!$criteria instanceof PaginableCriteriaInterface) {
            throw new Exception\ServiceNotCreatedException(sprintf(
                'Instance of type "%s" is invalid; must implement "%s"',
                (is_object($criteria) ? get_class($criteria) : gettype($criteria)),
                PaginableCriteriaInterface::class
            ));
        }
        return $criteria;
    }

    /**
     * Retrieve HydratorInterface object from service locator
     *
     * @param ContainerInterface $serviceLocator
     * @param $name
     * @return HydratorInterface
     * @throws Exception\RuntimeException
     */
    protected function getHydratorByName(ContainerInterface $container, $name)
    {
        if ($container->has('HydratorManager')) {
            $container = $container->get('HydratorManager');
        }

        $obj = $container->get($name);
        if (!$obj instanceof HydratorInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($obj) ? get_class($obj) : gettype($obj)),
                HydratorInterface::class
            ));
        }
        return $obj;
    }

    /**
     * Retrieve InputFilterInterface object from service locator
     *
     * @param ContainerInterface $container
     * @param $name
     * @return InputFilterInterface
     * @throws Exception\RuntimeException
     */
    protected function getInputFilterByName(ContainerInterface $container, $name)
    {
        if ($container->has('InputFilterManager')) {
            $container = $container->get('InputFilterManager');
        }

        $obj = $container->get($name);
        if (!$obj instanceof InputFilterInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($obj) ? get_class($obj) : gettype($obj)),
                InputFilterInterface::class
            ));
        }
        return $obj;
    }

    /**
     * Retrieve PrototypeStrategy object from service locator
     *
     * @param ContainerInterface $container
     * @param $name
     * @return InputFilterInterface
     * @throws Exception\RuntimeException
     */
    protected function getPrototypeStrategyByName(ContainerInterface $container, $name)
    {
        $obj = $container->get($name);
        if (!$obj instanceof PrototypeStrategyInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Instance of type %s is invalid; must implement %s',
                (is_object($obj) ? get_class($obj) : gettype($obj)),
                PrototypeStrategyInterface::class
            ));
        }
        return $obj;
    }
}
