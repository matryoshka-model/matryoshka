<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\PrototypeStrategy\Service;

use Interop\Container\ContainerInterface;
use Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy;
use Zend\ServiceManager\Factory\FactoryInterface;
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
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container);

        if (isset($config['service_locator'])) {
            $objectServiceLocator = $container->get($config['service_locator']);
        } else {
            $objectServiceLocator = $container->has('Matryoshka\Model\Object\ObjectManager') ?
                $container->get('Matryoshka\Model\Object\ObjectManager')
                : $container;
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
     * {@inheritdoc}
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
