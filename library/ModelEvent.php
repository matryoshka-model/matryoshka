<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\EventManager\Event;

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

    /**
     * {@inheritdoc}
     */
    public function setParam($name, $value)
    {
        switch ($name) {
            case 'criteria':
                $this->setCriteria($value);
                break;
            case 'resultSet':
                $this->setResultSet($value);
                break;
            default:
                parent::setParam($name, $value);
                break;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams($params)
    {
        parent::setParams($params);
        if (!is_array($params) && !$params instanceof \ArrayAccess) {
            return $this;
        }

        foreach (['criteria', 'resultSet'] as $param) {
            if (isset($params[$param])) {
                $method = 'set' . $param;
                $this->$method($params[$param]);
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParam($name, $default = null)
    {
        switch ($name) {
            case 'criteria':
                return $this->getCriteria();
            case 'resultSet':
                return $this->getResultSet();
            default:
                return parent::getParam($name, $default);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        $params = parent::getParams();
        if (is_array($params) || $params instanceof \ArrayAccess) {
            $params['criteria'] = $this->getCriteria();
            $params['resultSet'] = $this->getResultSet();
            return $params;
        }

        $params->criteria = $this->getCriteria();
        $params->resultSet = $this->getResultSet();
        return $params;
    }
}
