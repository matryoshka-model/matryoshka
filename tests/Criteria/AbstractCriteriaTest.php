<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria;

use MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria;

class AbstractCriteriaTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->criteria = new ConcreteCriteria();
    }

    public function testGetSetLimit()
    {
        $this->assertNull($this->criteria->getLimit()); //Test default

        $limit = 10;
        $this->assertInstanceOf('\MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria', $this->criteria->setLimit($limit));
        $this->assertAttributeEquals($limit, 'limit', $this->criteria);
        $this->assertSame($limit, $this->criteria->getLimit());

        $limit = null;
        $this->assertInstanceOf('\MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria', $this->criteria->setLimit($limit));
        $this->assertAttributeEquals($limit, 'limit', $this->criteria);
        $this->assertSame($limit, $this->criteria->getLimit());
    }

    public function testGetSetOffset()
    {
        $this->assertNull($this->criteria->getOffset()); //Test default

        $offset = 20;
        $this->assertInstanceOf('\MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria', $this->criteria->setOffset($offset));
        $this->assertAttributeEquals($offset, 'offset', $this->criteria);
        $this->assertSame($offset, $this->criteria->getOffset());

        $offset = null;
        $this->assertInstanceOf('\MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria', $this->criteria->setOffset($offset));
        $this->assertAttributeEquals($offset, 'offset', $this->criteria);
        $this->assertSame($offset, $this->criteria->getOffset());
    }

}
