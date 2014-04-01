<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria;


use MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria;

class CriteriaTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->criteria = new ConcreteCriteria();
    }

    public function testLimit()
    {
        $this->assertInstanceOf('\MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria', $this->criteria->limit(10));
        $this->assertAttributeEquals(10, 'limit', $this->criteria);
    }

    public function testOffset()
    {
        $this->assertInstanceOf('\MatryoshkaTest\Model\Criteria\TestAsset\ConcreteCriteria', $this->criteria->offset(20));
        $this->assertAttributeEquals(20, 'offset', $this->criteria);
    }

}
