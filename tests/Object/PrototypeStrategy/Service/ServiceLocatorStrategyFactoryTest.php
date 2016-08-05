<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object\PrototypeStrategy\Service;

use Matryoshka\Model\Object\PrototypeStrategy\Service\ServiceLocatorStrategyFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager;

/**
 * Class ServiceLocatorStrategyFactoryTest
 */
class ServiceLocatorStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    protected $testConfig =  [
        'type_field'        => 'foo',
        'validate_object'   => true,
        'clone_object'      => false,
    ];

    /**
     * @var string
     */
    protected $configKey = 'matryoshka-object-servicelocatorstrategy';

    /**
     * @return void
     */
    public function setUp()
    {
        $config = [
            $this->configKey => $this->testConfig,
        ];

        $this->serviceManager = new ServiceManager\ServiceManager([
                'factories' => [
                    'Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy' =>
                        'Matryoshka\Model\Object\PrototypeStrategy\Service\ServiceLocatorStrategyFactory',
                ]
            ]
        );

        $this->serviceManager->setService('Config', $config);
    }

    public function testCreateService()
    {
        $serviceLocator = $this->serviceManager;

        $strategy = $serviceLocator->get('Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy');
        $this->assertInstanceOf('\Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy', $strategy);
        $this->assertSame($serviceLocator, $strategy->getServiceLocator());
    }

    public function testCreateServiceWithSpecificServiceLocator()
    {
        $serviceLocator = $this->serviceManager;
        $serviceLocator->setAllowOverride(true);
        $serviceLocator->setService(
            'Config',
            [$this->configKey => ['service_locator' => 'CustomServiceLocator']]
        );
        $customServiceLocator = new \Zend\ServiceManager\ServiceManager();
        $serviceLocator->setService('CustomServiceLocator', $customServiceLocator);

        $strategy = $serviceLocator->get('Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy');
        $this->assertInstanceOf('\Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy', $strategy);
        $this->assertSame($customServiceLocator, $strategy->getServiceLocator());
    }

    public function testGetConfig()
    {
        $serviceLocator = $this->serviceManager;
        $factory =  new ServiceLocatorStrategyFactory();
        $serviceLocator->setFactory('strategy', $factory);
        $strategy = $serviceLocator->get('strategy');

        $this->assertSame($serviceLocator, $strategy->getServiceLocator());

        $this->assertSame($this->testConfig['type_field'], $strategy->getTypeField());
        $this->assertSame($this->testConfig['validate_object'], $strategy->getValidateObject());
        $this->assertSame($this->testConfig['clone_object'], $strategy->getCloneObject());

        $reflection = new \ReflectionClass($factory);
        $getConfigMethod = $reflection->getMethod('getConfig');
        $getConfigMethod->setAccessible(true);

        $this->assertSame($this->testConfig, $getConfigMethod->invoke($factory, $serviceLocator));

        //Test without Config service
        $factory = new ServiceLocatorStrategyFactory();
        $reflection = new \ReflectionClass($factory);
        $getConfigMethod = $reflection->getMethod('getConfig');
        $getConfigMethod->setAccessible(true);

        $this->assertSame([], $getConfigMethod->invoke($factory, new ServiceManager\ServiceManager()));

        //Test without config node
        $factory = new ServiceLocatorStrategyFactory();
        $reflection = new \ReflectionClass($factory);
        $getConfigMethod = $reflection->getMethod('getConfig');
        $getConfigMethod->setAccessible(true);
        $sm = new ServiceManager\ServiceManager();
        $sm->setService('Config', []);
        $this->assertSame([], $getConfigMethod->invoke($factory, $sm));
    }
}
