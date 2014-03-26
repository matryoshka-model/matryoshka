<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 26/03/14
 * Time: 16.14
 */

namespace MatryoshkaTest\Model\Criteria;

use Matryoshka\Model\Criteria\AbstractCriteria;
use Matryoshka\Model\ModelInterface;

class MockCriteria extends AbstractCriteria  {

    public function apply(ModelInterface $model)
    {
        return array();
    }

}
