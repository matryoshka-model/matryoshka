<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelStubInterface;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Interface PaginableCriteriaInterface
 *
 * Criterias implementing this interface are able to factory a paginator adapter.
 */
interface PaginableCriteriaInterface extends CriteriaInterface
{
    /**
     * @param ModelStubInterface $model
     * @return AdapterInterface
     */
    public function getPaginatorAdapter(ModelStubInterface $model);
}
