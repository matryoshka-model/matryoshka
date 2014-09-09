<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria\ActiveRecord;

use Matryoshka\Model\Exception;
use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;

/**
 * Class AbstractCriteria
 *
 * A particular kind of CriteriaInterface in order to work with an Active Record object.
 */
abstract class AbstractCriteria implements
    CriteriaInterface,
    WritableCriteriaInterface,
    DeletableCriteriaInterface
{

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
            throw new Exception\RuntimeException('In order to work with ActiveRecord criteria the id must be set');
        }
        return $this->id;
    }


}
