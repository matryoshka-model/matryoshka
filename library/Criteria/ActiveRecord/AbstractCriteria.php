<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria\ActiveRecord;

use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\ReadableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Criteria\IdentityCriteriaInterface;

/**
 * Class AbstractCriteria
 *
 * A particular kind of CriteriaInterface aimed to work with ActiveRecord objects.
 * This criteria works with one object at time.
 * For read and delete operations an id must be set using {@link setId()}.
 */
abstract class AbstractCriteria implements
    ReadableCriteriaInterface,
    WritableCriteriaInterface,
    DeletableCriteriaInterface,
    IdentityCriteriaInterface
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
        if (null === $this->id) {
            throw new Exception\RuntimeException(
                'getId(), apply() and applyDelete() require that an id must be present using a prior call to setId()'
            );
        }
        return $this->id;
    }

    /**
     * Check if Id has been set
     *
     * @return boolean
     */
    public function hasId()
    {
        return $this->id !== null;
    }
}
