<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Object\Service;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ObjectManagerFactoryTest
 */
class ObjectManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config = [
            'matryoshka' => [
                'object_manager' => [
                    'invokables' => [
                        'test' => 'stdClass'
                    ],
                    'aliases' => [
                        'test-alias' => 'test',
                    ]
                ]
            ]
        ];

        $serviceManager = new ServiceManager([
                'factories' => [
                    'ObjectManager' => 'Matryoshka\Model\Object\Service\ObjectManagerFactory',
                ]
            ]
        );
        $serviceManager->setService('Config', $config);

        $objectManager = $serviceManager->get('ObjectManager');
        $this->assertInstanceOf('\Matryoshka\Model\Object\ObjectManager', $objectManager);

        $this->assertTrue($objectManager->has('test'));
        $this->assertInstanceOf('stdClass', $objectManager->get('test'));

        $this->assertTrue($objectManager->has('test-alias'));
        $this->assertInstanceOf('stdClass', $objectManager->get('test-alias'));
    }
}
