<?php
/**
 * Created by visa
 * Date:  28/08/14 16.53
 * Class: ObjectFactoryTest.php
 */

namespace MatryoshkaTest\Model\Object;


use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

class ObjectFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
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
                    'ObjectManager' => 'Matryoshka\Model\Object\ObjectFactory',
                ]
            ])
        );
        $serviceManager->setService('Config', $config);

        $this->assertTrue($serviceManager->get('ObjectManager')->has('test'));
        $this->assertInstanceOf('stdClass', $serviceManager->get('ObjectManager')->get('test'));

    }
} 