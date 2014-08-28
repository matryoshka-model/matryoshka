<?php

namespace MatryoshkaTest\Model\Mock\Criteria;

use Matryoshka\Model\Criteria\AbstractCriteria;
use Matryoshka\Model\ModelInterface;

class MockCriteria extends AbstractCriteria
{

    public function apply(ModelInterface $model)
    {
        return array();
    }

}
