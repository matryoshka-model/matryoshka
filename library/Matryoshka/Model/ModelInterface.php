<?php
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\ResultSet\ResultSetInterface;

interface ModelInterface
{
    /**
     * @param CriteriaInterface $criteria
     * @return ResultSetInterface
     */
    public function find(CriteriaInterface $criteria);
}