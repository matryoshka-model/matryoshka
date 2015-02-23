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
    const EVENT_SAVE_PRE = 'save.pre';
    const EVENT_SAVE_POST = 'save.post';
    const EVENT_DELETE_PRE = 'delete.pre';
    const EVENT_DELETE_POST = 'delete.post';
    const EVENT_FIND_PRE = 'find.pre';
    const EVENT_FIND_POST= 'find.post';

    protected $specializedParams = ['criteria', 'data', 'result', 'resultSet'];

    /**
     * @var null|CriteriaInterface
     */
    protected $criteria;

    /**
     * @var null|array|object
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $result;

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
     * @param null|array|object $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return null|array|object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
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
        if (in_array($name, $this->specializedParams)) {
            $this->{'set'.$name}($value);
        } else {
            parent::setParam($name, $value);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams($params)
    {
        parent::setParams($params);
        $isObject = is_object($this->params);
        foreach ($this->specializedParams as $param) {
            if ($isObject) {
                if (isset($params->{$param})) {
                    $method = 'set' . $param;
                    $this->$method($params->{$param});
                }
                unset($this->params->{$param});
            } else {
                if (isset($params[$param])) {
                    $method = 'set' . $param;
                    $this->$method($params[$param]);
                }
                unset($this->params[$param]);
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParam($name, $default = null)
    {
        if (in_array($name, $this->specializedParams)) {
            return $this->{'get'.$name}();
        }
        return parent::getParam($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        $params = parent::getParams();
        $isObject = is_object($params);
        foreach ($this->specializedParams as $param) {
            if ($isObject) {
                $params->{$param} = $this->{'get'.$param}();
            } else {
                $params[$param] = $this->{'get'.$param}();
            }
        }
        return $params;
    }
}
