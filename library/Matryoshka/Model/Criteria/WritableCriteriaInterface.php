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
 * Interface WritableCriteriaInterface
 */
interface WritableCriteriaInterface
{


    /**
     * @param ModelInterface $model
     * @param array $data
     */
    public function applyWrite(ModelInterface $model, array $data);
}
