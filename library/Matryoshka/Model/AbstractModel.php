<?php
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;


abstract class AbstractModel implements ModelInterface
{


    protected $dataGateway;

    /**
     * @var ResultSetInterface
     */
    protected $resultSetPrototype;

    public function getDataGateway()
    {
        return $this->dataGateway;
    }

    /**
     * @return ResultSetInterface
     */
    public function getResultSetPrototype()
    {
        return $this->resultSetPrototype;
    }

    /**
     * @param CriteriaInterface $criteria
     * @return ResultSetInterface
     */
    public function find(CriteriaInterface $criteria)
    {
        $result = $criteria->apply($this->getDataGateway());

        $resultSet = clone $this->getResultSetPrototype();
        $resultSet->initialize($result);
        return $resultSet;
    }
}