<?php
namespace MatryoshkaTest\Model\Object\ActiveRecord;

use MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria;
use MatryoshkaTest\Model\TestAsset\ActiveRecordObject;

/**
 * Class AbstractActiveRecordTest
 */
class AbstractActiveRecordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ActiveRecordObject
     */
    protected $object;

    public function setUp()
    {
        $this->object = new ActiveRecordObject();
        $this->criteria = new ConcreteCriteria();
        $this->object->setActiveRecordCriteriaPrototype($this->criteria);
    }

    public function testSetModel()
    {
        $this->assertSame($this->object, $this->object->setId('foo'));
        $this->assertAttributeEquals('foo', 'id', $this->object);
    }

    public function testGetModel()
    {
        $this->assertNull($this->object->getId());
        $this->object->setId('bar');
        $this->assertEquals('bar', $this->object->getId());
    }

    public function testSave()
    {
        $id = 'id';
        $criteria = clone $this->criteria;
        $criteria->setId($id);


        $abstractModelMock  = $this->getMockBuilder('Matryoshka\Model\AbstractModel')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['save'])
                                    ->getMock();
        $result = null;


        $abstractModelMock->expects($this->atLeastOnce())
                           ->method('save')
                           ->with($this->equalTo($criteria), $this->identicalTo($this->object))
                           ->will($this->returnValue($result));

        $this->object->setModel($abstractModelMock);

        //Test with ID
        $this->object->setId($id);
        $this->assertSame($result, $this->object->save());
    }


    public function testSaveShouldThrowExceptionWhenCriteriaNotPresent()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');
        $object = new ActiveRecordObject();
        $object->save();
    }

    public function testSaveShouldThrowExceptionWhenModelNotPresent()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');
        $this->object->save();
    }


    public function testDelete()
    {
        $abstractModelMock  = $this->getMockBuilder('Matryoshka\Model\AbstractModel')
                            ->disableOriginalConstructor()
                            ->setMethods(['save', 'delete'])
                            ->getMock();
        $result = null;
        $abstractModelMock->expects($this->atLeastOnce())
                        ->method('delete')
                        ->with($this->isInstanceOf('Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria'))
                        ->will($this->returnValue($result));

        $this->object->setModel($abstractModelMock);
        $this->object->setId('id');

        $this->assertSame($result, $this->object->delete());
    }

    public function testDeleteShouldThrowExceptionWhenIdNotPresent()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');
        $this->object->delete();
    }

    public function testDeleteShouldThrowExceptionWhenCriteriaNotPresent()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');
        $object = new ActiveRecordObject();
        $object->setId('foo')->delete();
    }

    public function testDeleteShouldThrowExceptionWhenModelNotPresent()
    {
        $this->setExpectedException('Matryoshka\Model\Exception\RuntimeException');
        $this->object->setId('foo')->delete();
    }


    /**
     * @expectedException \Matryoshka\Model\Exception\InvalidArgumentException
     * @testdox Set exception
     */
    public function testException__set()
    {
        $this->object->invalidProperty = 22;
    }

    /**
     * @expectedException \Matryoshka\Model\Exception\InvalidArgumentException
     * @testdox Get exception
     */
    public function testException__get()
    {
        $test =  $this->object->invalidProperty;
    }

    /**
     * @expectedException \Matryoshka\Model\Exception\InvalidArgumentException
     * @testdox Set exception
     */
    public function testException__unset()
    {
        unset($this->object->invalidProperty);
    }
}
