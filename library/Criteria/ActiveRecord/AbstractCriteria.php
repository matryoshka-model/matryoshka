<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria\ActiveRecord;

use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\ReadableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Exception;

/**
 * Class AbstractCriteria
 *
 * A particular kind of CriteriaInterface in order to work with an ActiveRecord object.
 * This criteria works always with just one object at time. For read and delete operations
 * an id must be set using setId().
 * @todo Define the applyWrite behavior releted to the id presence
 *
 */
abstract class AbstractCriteria implements
    ReadableCriteriaInterface,
    WritableCriteriaInterface,
    DeletableCriteriaInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * Set Id
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Id
     * @return mixed
     */
    public function getId()
    {
        if (!$this->id) {
            throw new Exception\RuntimeException(
                'getId(), apply() and applyDelete() require that an id must be present using a prior call to setId()'
            );
        }
        return $this->id;
    }
}
