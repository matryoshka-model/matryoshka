<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Mock\Criteria;

use Matryoshka\Model\Criteria\AbstractCriteria;
use Matryoshka\Model\ModelStubInterface;

/**
 * Class MockCriteria
 */
class MockCriteria extends AbstractCriteria
{

    /**
     * {@inheritdoc}
     */
    public function apply(ModelStubInterface $model)
    {
        return [];
    }
}
