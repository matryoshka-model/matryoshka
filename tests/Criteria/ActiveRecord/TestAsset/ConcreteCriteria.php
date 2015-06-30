<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria\ActiveRecord\TestAsset;

use Matryoshka\Model\Criteria\ActiveRecord\AbstractCriteria;
use Matryoshka\Model\ModelStubInterface;

/**
 * Class ConcreteCriteria
 */
class ConcreteCriteria extends AbstractCriteria
{
    public function apply(ModelStubInterface $model)
    {
        return [];
    }

    public function applyWrite(ModelStubInterface $model, array &$data)
    {
        return false;
    }

    public function applyDelete(ModelStubInterface $model)
    {
        return false;
    }
}
