<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Mock\Criteria;

use Matryoshka\Model\Criteria\AbstractCriteria;
use Matryoshka\Model\ModelInterface;

class MockCriteria extends AbstractCriteria
{

    public function apply(ModelInterface $model)
    {
        return [];
    }

}
