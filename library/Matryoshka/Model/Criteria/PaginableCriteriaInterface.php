<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelInterface;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Interface PaginableCriteriaInterface
 */
interface PaginableCriteriaInterface extends CriteriaInterface
{

    /**
     * @param ModelInterface $model
     * @return AdapterInterface
     */
    public function getPaginatorAdapter(ModelInterface $model);
}
