<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria\ActiveRecord;

use MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria;

/**
 * Class AbstractCriteriaTest
 */
class AbstractCriteriaTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->criteria = new ConcreteCriteria();
    }


    public function testShouldThrowExceptionWhenNoId()
    {
        $this->setExpectedException('\Matryoshka\Model\Exception\RuntimeException');
        $this->criteria->getId();
    }

    /**
     * @depends testShouldThrowExceptionWhenNoId
     */
    public function testGetSetHasId()
    {
        $this->assertFalse($this->criteria->hasId());

        $id = 'foo';
        $this->assertInstanceOf(
            '\MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset\ConcreteCriteria',
            $this->criteria->setId($id)
        );
        $this->assertSame($id, $this->criteria->getId());

        $this->assertTrue($this->criteria->hasId());
        $this->criteria->setId(null);
        $this->assertFalse($this->criteria->hasId());
    }

}
