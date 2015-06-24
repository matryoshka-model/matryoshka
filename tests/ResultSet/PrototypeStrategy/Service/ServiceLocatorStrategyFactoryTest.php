<?php
namespace MatryoshkaTest\Model\ResultSet\PrototypeStrategy\Service;

use Matryoshka\Model\ResultSet\PrototypeStrategy\Service\ServiceLocatorStrategyFactory;
use MatryoshkaTest\Model\Object\PrototypeStrategy\Service\ServiceLocatorStrategyFactoryTest as ObjectServiceLocatorStrategyFactoryTest;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ServiceLocatorStrategyFactoryTest
 *
 * NOTE: remove this test when the relative class (already deprecated) will be deleted
 */
class ServiceLocatorStrategyFactoryTest extends ObjectServiceLocatorStrategyFactoryTest
{
    /**
     * {@inheritdoc}
     */
    protected $configKey = 'matryoshka-resultset-servicelocatorstrategy';

    /**
     * @return void
     */
    public function setUp()
    {
        $config = [
            $this->configKey => $this->testConfig,
        ];

        $sm = $this->serviceManager = new ServiceManager(
            new ServiceManagerConfig([
                'factories' => [
                    'Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy' =>
                    'Matryoshka\Model\ResultSet\PrototypeStrategy\Service\ServiceLocatorStrategyFactory',
                ]
            ])
        );

        $sm->setService('Config', $config);
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

        $this->assertSame([], $getConfigMethod->invoke($factory, new ServiceManager()));

        //Test without config node
        $factory = new ServiceLocatorStrategyFactory();
        $reflection = new \ReflectionClass($factory);
        $getConfigMethod = $reflection->getMethod('getConfig');
        $getConfigMethod->setAccessible(true);
        $sm = new ServiceManager();
        $sm->setService('Config', []);
        $this->assertSame([], $getConfigMethod->invoke($factory, $sm));
    }
}
