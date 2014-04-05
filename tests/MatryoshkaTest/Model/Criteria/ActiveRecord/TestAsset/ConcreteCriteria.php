<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset;

use Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria;
use Matryoshka\Model\ModelInterface;

class ConcreteCriteria extends AbstractCriteria
{
    public function apply(ModelInterface $model)
    {
        return array();
    }

    public function applyWrite(ModelInterface $model, array &$data)
    {
        return false;
    }

    public function applyDelete(ModelInterface $model)
    {
        return false;
    }

}