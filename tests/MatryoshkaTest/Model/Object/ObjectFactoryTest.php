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
        $config = array(
            'object_manager' => array(
                'invokables' => array(
                    'test' => 'stdClass'
                )
            )
        );

        $serviceManager = new ServiceManager(
            new Config(array(
                'factories' => array(
                    'ObjectManager' => 'Matryoshka\Model\Object\ObjectFactory',
                )
            ))
        );
        $serviceManager->setService('Config', $config);

        $this->assertTrue($serviceManager->get('ObjectManager')->has('test'));
        $this->assertInstanceOf('stdClass', $serviceManager->get('ObjectManager')->get('test'));

    }
} 