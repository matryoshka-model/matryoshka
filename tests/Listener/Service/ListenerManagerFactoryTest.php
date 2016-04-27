<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Listener\Service;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ListenerManagerFactoryTest
 */
class ListenerManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config = [
            'matryoshka' => [
                'listener_manager' => [
                    'invokables' => [
                        'test' => 'MatryoshkaTest\Model\TestAsset\ListenerAggregate'
                    ],
                    'aliases' => [
                        'test-alias' => 'test',
                    ]
                ]
            ]
        ];

        $serviceManager = new ServiceManager(
            new Config([
                'factories' => [
                    'ListenerManager' => 'Matryoshka\Model\Listener\Service\ListenerManagerFactory',
                ]
            ])
        );
        $serviceManager->setService('Config', $config);

        $listenerManager = $serviceManager->get('ListenerManager');
        $this->assertInstanceOf('\Matryoshka\Model\Listener\ListenerManager', $listenerManager);

        $this->assertTrue($listenerManager->has('test'));
        $this->assertInstanceOf('MatryoshkaTest\Model\TestAsset\ListenerAggregate', $listenerManager->get('test'));

        $this->assertTrue($listenerManager->has('test-alias'));
        $this->assertInstanceOf('MatryoshkaTest\Model\TestAsset\ListenerAggregate', $listenerManager->get('test-alias'));
    }
}
