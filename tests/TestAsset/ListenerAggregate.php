<?php
namespace MatryoshkaTest\Model\TestAsset;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\EventManagerInterface;

class ListenerAggregate implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {

    }


}