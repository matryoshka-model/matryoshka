<?php
namespace MatryoshkaTest\Model\Object\Service;


use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

class ObjectManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config = [
            'object_manager' => [
                'invokables' => [
                    'test' => 'stdClass'
                ]
            ]
        ];

        $serviceManager = new ServiceManager(
            new Config([
                'factories' => [
                    'ObjectManager' => 'Matryoshka\Model\Object\Service\ObjectManagerFactory',
                ]
            ])
        );
        $serviceManager->setService('Config', $config);

        $objectManager = $serviceManager->get('ObjectManager');
        $this->assertInstanceOf('\Matryoshka\Model\Object\ObjectManager', $objectManager);

        $this->assertTrue($objectManager->has('test'));
        $this->assertInstanceOf('stdClass', $objectManager->get('test'));

    }
}