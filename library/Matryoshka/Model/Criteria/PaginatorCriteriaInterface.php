<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelInterface;

/**
 * Interface PaginatorCriteriaInterface
 */
interface PaginatorCriteriaInterface
{

    /**
     * @param ModelInterface $model
     */
    public function getPaginatorAdapter(ModelInterface $model);
}
