<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Criteria\DeletableCriteriaInterface;
use Matryoshka\Model\Criteria\ReadableCriteriaInterface;
use Matryoshka\Model\Criteria\WritableCriteriaInterface;
use Matryoshka\Model\Exception;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Matryoshka\Model\ResultSet\ResultSetInterface;

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
        // Ensure that object and resultset prototypes have been set
        $this->getObjectPrototype();

        $event = $this->getEvent();
        $event->setCriteria($criteria);

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_FIND_PRE, $event, function ($r) {
            return $r instanceof ResultSetInterface;
        });

        if ($results->stopped()) {
            $last = $results->last();
            if ($last instanceof ResultSetInterface) {
                $event->setResultSet($last);
                return $last;
            }
        }

        $event->setResultSet(parent::find($criteria));

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_FIND_POST, $event, function ($r) {
            return $r instanceof ResultSetInterface;
        });

        if ($results->stopped()) {
            $last = $results->last();
            if ($last instanceof ResultSetInterface) {
                $event->setResultSet($last);
            }
        }

        return $event->getResultSet();
    }

    /**
     * {@inheritdoc}
     */
    public function save(WritableCriteriaInterface $criteria, $dataOrObject)
    {
        $event = $this->getEvent();
        $event->setCriteria($criteria);
        $event->setData($dataOrObject);

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_SAVE_PRE, $event, function ($r) {
            return is_int($r);
        });

        if ($results->stopped()) {
            $last = $results->last();
            if (null === $last || is_int($last)) {
                $event->setResult($last);
                return $last;
            }
        }

        $event->setResult(parent::save($event->getCriteria(), $event->getData()));

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_SAVE_POST, $event, function ($r) {
            return is_int($r);
        });

        if ($results->stopped()) {
            $last = $results->last();
            if (null === $last || is_int($last)) {
                $event->setResult($last);
            }
        }

        return $event->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(DeletableCriteriaInterface $criteria)
    {
        $event = $this->getEvent();
        $event->setCriteria($criteria);

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_DELETE_PRE, $event, function ($r) {
            return is_int($r);
        });

        if ($results->stopped()) {
            $last = $results->last();
            if (null === $last || is_int($last)) {
                $event->setResult($last);
                return $last;
            }
        }

        $event->setResult(parent::delete($event->getCriteria()));

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_DELETE_POST, $event, function ($r) {
            return is_int($r);
        });

        if ($results->stopped()) {
            $last = $results->last();
            if (null === $last || is_int($last)) {
                $event->setResult($last);
            }
        }

        return $event->getResult();
    }
}
