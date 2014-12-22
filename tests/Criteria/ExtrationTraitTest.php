<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria;

use MatryoshkaTest\Model\TestAsset\ConcreteAbstractModel;
use Matryoshka\Model\Criteria\ExtractionTrait;
use Matryoshka\Model\ResultSet\ArrayObjectResultSet;
use Matryoshka\Model\ResultSet\HydratingResultSet;
use MatryoshkaTest\Model\TestAsset\HydratorAwareObject;
use MatryoshkaTest\Model\TestAsset\ActiveRecordObject;

class ExtrationTraitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ExtractionTrait
     */
    protected $extractionTrait;

    protected $testModel;

    protected $modelHydratorMock;

    protected $objectHydratorMock;

    public function setUp()
    {
        $this->extractionTrait = $this->getObjectForTrait(
            'Matryoshka\Model\Criteria\ExtractionTrait'
        );

        $this->modelHydratorMock = $this->getMockBuilder('\Zend\Stdlib\Hydrator\AbstractHydrator')
            ->disableOriginalConstructor()
            ->setMethods(['extractValue', 'extractName'])
            ->getMockForAbstractClass();

        $this->objectHydratorMock = $this->getMockBuilder('\Zend\Stdlib\Hydrator\AbstractHydrator')
            ->disableOriginalConstructor()
            ->setMethods(['hydrateName', 'extractName'])
            ->getMockForAbstractClass();

        $object    = new ActiveRecordObject();
        $object->setHydrator($this->objectHydratorMock);
        $resultSet = new HydratingResultSet();
        $resultSet->setObjectPrototype($object);
        $resultSet->setHydrator($this->modelHydratorMock);


        $this->testModel = new ConcreteAbstractModel();
        $this->testModel->setResultSetPrototype($resultSet);
        $this->testModel->setHydrator($this->modelHydratorMock);
    }

    public function testExtractValue()
    {
        $reflection = new \ReflectionClass($this->extractionTrait);
        $reflMethod = $reflection->getMethod('extractValue');
        $reflMethod->setAccessible(true);


        $hydratorMock = $this->modelHydratorMock;

        $hydratorMock->expects($this->atLeastOnce())
                     ->method('extractValue')
                     ->with($this->equalTo('modelName'), $this->equalTo('modelValue'))
                     ->willReturn('dataGatewayValue');



        $this->assertEquals(
            'dataGatewayValue',
            $reflMethod->invoke($this->extractionTrait, $this->testModel, 'modelName', 'modelValue', false)
        );

        // Test with name extraction
        $this->testModel->getResultSetPrototype()->setObjectPrototype(new \ArrayObject([])); // remove object hydrator
        $hydratorMock->expects($this->atLeastOnce())
                    ->method('extractName')
                    ->with($this->equalTo('dataGatewayName'))
                    ->willReturn('modelName');

        $this->assertEquals(
            'dataGatewayValue',
            $reflMethod->invoke($this->extractionTrait, $this->testModel, 'dataGatewayName', 'modelValue', true)
        );
    }

    public function testExtractValueShouldThrowExceptionWhenInvalidModelHydrator()
    {
        $reflection = new \ReflectionClass($this->extractionTrait);
        $reflMethod = $reflection->getMethod('extractValue');
        $reflMethod->setAccessible(true);

        $hydrator = $this->getMockForAbstractClass('\Zend\Stdlib\Hydrator\HydratorInterface');
        $this->testModel->setHydrator($hydrator); // set a not AbstractHydrator instance

        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');
        $reflMethod->invoke($this->extractionTrait, $this->testModel, 'dataGatewayName', 'modelValue');
    }

    public function testExtractName()
    {
        $reflection = new \ReflectionClass($this->extractionTrait);
        $reflMethod = $reflection->getMethod('extractName');
        $reflMethod->setAccessible(true);

        $objectHydratorMock = $this->objectHydratorMock;
        $objectHydratorMock->expects($this->atLeastOnce())
            ->method('hydrateName')
            ->with($this->equalTo('objectName'))
            ->willReturn('modelName');

        $hydratorMock = $this->modelHydratorMock;
        $hydratorMock->expects($this->atLeastOnce())
            ->method('extractName')
            ->with($this->equalTo('modelName'))
            ->willReturn('dataGatewayName');


        $this->assertEquals(
            'dataGatewayName',
            $reflMethod->invoke($this->extractionTrait, $this->testModel, 'objectName')
        );

        // Test without object hydrator
        $this->testModel->getResultSetPrototype()->setObjectPrototype(new \ArrayObject([])); // remove object hydrator

        $this->assertEquals(
            'dataGatewayName',
            $reflMethod->invoke($this->extractionTrait, $this->testModel, 'modelName')
        );
    }

    public function testExtractNameShouldThrowExceptionWhenInvalidObjectHydrator()
    {
        $reflection = new \ReflectionClass($this->extractionTrait);
        $reflMethod = $reflection->getMethod('extractName');
        $reflMethod->setAccessible(true);

        $hydrator = $this->getMockForAbstractClass('\Zend\Stdlib\Hydrator\HydratorInterface');
        $this->testModel->getObjectPrototype()->setHydrator($hydrator); // set a not AbstractHydrator instance

        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');

        $reflMethod->invoke($this->extractionTrait, $this->testModel, 'objectName');
    }

    public function testExtractNameShouldThrowExceptionWhenInvalidModelHydrator()
    {
        $reflection = new \ReflectionClass($this->extractionTrait);
        $reflMethod = $reflection->getMethod('extractName');
        $reflMethod->setAccessible(true);

        $hydrator = $this->getMockForAbstractClass('\Zend\Stdlib\Hydrator\HydratorInterface');
        $this->testModel->setHydrator($hydrator); // set a not AbstractHydrator instance

        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');

        $reflMethod->invoke($this->extractionTrait, $this->testModel, 'objectName');
    }


}