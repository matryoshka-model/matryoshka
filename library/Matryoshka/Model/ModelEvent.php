<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;


use Zend\EventManager\Event;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\Exception;

/**
 * Class ModelEvent
 */
class ModelEvent extends Event
{
    /**
     * @var null|CriteriaInterface
     */
    protected $criteria;

    /**
     * @var null|ResultSetInterface
     */
    protected $resultSet;

    /**
     * {@inheritdoc}
     * @throws Exception\InvalidArgumentException
     */
    public function setTarget($target)
    {
        if (!$target instanceof ModelInterface) {
            throw new Exception\InvalidArgumentException(__CLASS__ . ' works only with ModelInterface targets');
        }
        return parent::setTarget($target);
    }

    /**
     * @param CriteriaInterface $criteria
     * @return $this
     */
    public function setCriteria(CriteriaInterface $criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * @return CriteriaInterface
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param ResultSetInterface $resultSet
     * @return $this
     */
    public function setResultSet(ResultSetInterface $resultSet)
    {
        $this->resultSet = $resultSet;
        return $this;
    }

    /**
     * @return null|ResultSetInterface
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

}
