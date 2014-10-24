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
        // Ensure that object and resultset prototypes have been set
        $this->getObjectPrototype();

        $event = $this->getEvent();
        $event->setCriteria($criteria);

        $results = $this->getEventManager()->trigger(ModelEvent::EVENT_FIND_PRE, $event);

        if ($results->stopped()) {
            $last = $results->last();
            $resultSetPrototype = $this->getResultSetPrototype();
            if (!$last instanceof $resultSetPrototype) {
                throw new Exception\RuntimeException(sprintf(
                    '%s expects that last event response is a "%s"; received "%s"',
                    __METHOD__,
                    get_class($resultSetPrototype),
                    is_object($last) ? get_class($last) : gettype($last)
                ));
            }
            return $last;
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
            $last = $results->last();
            if (null !== $last && !is_int($last)) {
                throw new Exception\RuntimeException(sprintf(
                    '%s expects that last event response is an integer or null; received "%s"',
                    __METHOD__,
                    is_object($last) ? get_class($last) : gettype($last)
                ));
            }
            return $last;
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
            $last = $results->last();
            if (null !== $last && !is_int($last)) {
                throw new Exception\RuntimeException(sprintf(
                    '%s expects that last event response is an integer or null; received "%s"',
                    __METHOD__,
                    is_object($last) ? get_class($last) : gettype($last)
                ));
            }
            return $last;
        }

        $return = parent::delete($criteria);

        $this->getEventManager()->trigger(ModelEvent::EVENT_DELETE_POST, $event);
        return $return;
    }
}
