<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria\ObjectGateway;

use Matryoshka\Model\Exception;
use Matryoshka\Model\Criteria\AbstractCriteria as BaseCriteria;
use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;

/**
 * Class AbstractPaginatorCriteria
 */
abstract class AbstractCriteria implements CriteriaInterface, WritableCriteriaInterface, DeletableCriteriaInterface
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
            throw Exception\RuntimeException('In order to work with ObjectGateway criteria the id must be set');
        }
        return $this->id;
    }


}