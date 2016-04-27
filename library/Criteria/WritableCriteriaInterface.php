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

/**
 * Interface WritableCriteriaInterface
 *
 * Criterias implementing this interface are able to perform write operations.
 */
interface WritableCriteriaInterface extends CriteriaInterface
{
    /**
     * @param ModelStubInterface $model
     * @param array $data
     */
    public function applyWrite(ModelStubInterface $model, array &$data);
}
