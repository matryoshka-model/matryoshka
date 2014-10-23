<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\ReadableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Exception;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Class ObservableModel
 */
class ObservableModel extends Model implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Create and return ModelEvent
     *
     * @return ModelEvent
     */
    protected function getEvent()
    {
        $event = new ModelEvent();
        $event->setTarget($this);
        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function find(ReadableCriteriaInterface $criteria)
    {
        $event = $this->getEvent();
        $event->setCriteria($criteria);
        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_FIND_PRE, $event);

        if ($results->stopped()) {
            $resultSet = clone $this->getResultSetPrototype();
            $resultSet->initialize([]);
            return $resultSet;
        }

        $return = parent::find($criteria);
        $event->setResultSet($return);

        $this->getEventManager()->trigger(ModelEvent::EVENT_FIND_POST, $event);
        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject)
    {
        $event = $this->getEvent();
        $event->setCriteria($criteria);
        $event->setParam('data', $dataOrObject);
        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_SAVE_PRE, $event);

        if ($results->stopped()) {
            return null;
        }

        $return = parent::save($criteria, $dataOrObject);

        $this->getEventManager()->trigger(ModelEvent::EVENT_SAVE_POST, $event);
        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(DeletableCriteriaInterface $criteria)
    {
        $event = $this->getEvent();
        $event->setCriteria($criteria);
        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_DELETE_PRE, $event);

        if ($results->stopped()) {
            return null;
        }

        $return = parent::delete($criteria);

        $this->getEventManager()->trigger(ModelEvent::EVENT_DELETE_POST, $event);
        return $return;
    }
}
