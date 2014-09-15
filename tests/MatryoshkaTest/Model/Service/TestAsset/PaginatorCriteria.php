<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service\TestAsset;

use Matryoshka\Model\Criteria\PaginableCriteriaInterface;
use Matryoshka\Model\ModelInterface;
use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\ArrayAdapter;

/**
 * Class PaginatorCriteria
 */
class PaginatorCriteria implements PaginableCriteriaInterface
{
    /**
     * @param ModelInterface $model
     * @return AdapterInterface
     */
    public function getPaginatorAdapter(ModelInterface $model)
    {
        return new ArrayAdapter();
    }
}
