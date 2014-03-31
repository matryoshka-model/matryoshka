<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Criteria\CriteriaInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Model extends AbstractModel implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @param $dataGateway
     * @param ResultSetInterface $resultSetPrototype
     */
    public function __construct($dataGateway, ResultSetInterface $resultSetPrototype, HydratorInterface $hydrator = null)
    {
        $this->setDataGateway($dataGateway);

        if($hydrator) {
            $this->setHydrator($hydrator);
        }

        $this->setResultSetPrototype($resultSetPrototype);
    }
}