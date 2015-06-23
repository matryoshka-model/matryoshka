<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\ModelStubInterface;

/**
 * Interface DeletableCriteriaInterface
 *
 * Criterias implementing this interface are able to perform delete operations.
 */
interface DeletableCriteriaInterface extends CriteriaInterface
{
    /**
     * @param ModelStubInterface $model
     */
    public function applyDelete(ModelStubInterface $model);
}
