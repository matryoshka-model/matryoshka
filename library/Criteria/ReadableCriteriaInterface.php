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
 * Interface ReadableCriteriaInterface
 *
 */
interface ReadableCriteriaInterface extends CriteriaInterface
{
    /**
     * Apply
     * @param ModelStubInterface $model
     * @return mixed
     */
    public function apply(ModelStubInterface $model);
}
