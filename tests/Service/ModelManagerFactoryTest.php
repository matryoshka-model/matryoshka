<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

use Matryoshka\Model\ResultSet\HydratingResultSet;
use MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ModelManagerFactoryTest
 */
class ModelManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config = [
            'matryoshka' => [
                'model_manager' => [
                    'invokables' => [
                        'MatryoshkaTest\Model\TestAsset\InvokableModel' => 'MatryoshkaTest\Model\TestAsset\InvokableModel'
                    ]
                ],
            ]
        ];

        $serviceManager = new ServiceManager(
            new Config([
                'factories' => [
                    'ModelManager' => 'Matryoshka\Model\Service\ModelManagerFactory',
                ]
            ])
        );
        $serviceManager->setService('Config', $config);
        $serviceManager->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new FakeDataGateway);
        $serviceManager->setService('Matryoshka\Model\ResultSet\HydratingResultSet', new HydratingResultSet);

        $modelManager = $serviceManager->get('ModelManager');
        $this->assertInstanceOf('\Matryoshka\Model\ModelManager', $modelManager);

        $this->assertTrue($modelManager->has('MatryoshkaTest\Model\TestAsset\InvokableModel'));
        $this->assertInstanceOf('\Matryoshka\Model\ModelInterface', $modelManager->get('MatryoshkaTest\Model\TestAsset\InvokableModel'));
    }
}
