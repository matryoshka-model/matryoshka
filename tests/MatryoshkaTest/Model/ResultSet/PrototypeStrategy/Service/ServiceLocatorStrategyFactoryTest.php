<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\ResultSet\PrototypeStrategy\Service;

use Matryoshka\Model\ResultSet\PrototypeStrategy\Service\ServiceLocatorStrategyFactory;
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
     * @return void
     */
    public function setUp()
    {
        $config = [
            'matryoshka-resultset-servicelocatorstrategy' => $this->testConfig,
        ];

        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig([
                'factories' => [
                    'Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy' =>
                    'Matryoshka\Model\ResultSet\PrototypeStrategy\Service\ServiceLocatorStrategyFactory',
                ]
            ])
        );

        $sm->setService('Config', $config);

    }

    public function testCreateService()
    {
        $serviceLocator = $this->serviceManager;

        $strategy = $serviceLocator->get('Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy');
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy', $strategy);
        $this->assertSame($serviceLocator, $strategy->getServiceLocator());
    }

    public function testCreateServiceWithSpecificServiceLocator()
    {
        $serviceLocator = $this->serviceManager;
        $serviceLocator->setAllowOverride(true);
        $serviceLocator->setService('Config', ['matryoshka-resultset-servicelocatorstrategy' => ['service_locator' => 'CustomServiceLocator']]);
        $customServiceLocator = new \Zend\ServiceManager\ServiceManager();
        $serviceLocator->setService('CustomServiceLocator', $customServiceLocator);

        $strategy = $serviceLocator->get('Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy');
        $this->assertInstanceOf('\Matryoshka\Model\ResultSet\PrototypeStrategy\ServiceLocatorStrategy', $strategy);
        $this->assertSame($customServiceLocator, $strategy->getServiceLocator());
    }

    public function testGetConfig()
    {
        $serviceLocator = $this->serviceManager;
        $factory = new ServiceLocatorStrategyFactory();
        $strategy = $factory->createService($serviceLocator);

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
