<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class Model
 */
class Model extends AbstractModel implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Ctor
     * @param mixed              $dataGateway
     * @param ResultSetInterface $resultSetPrototype
     */
    public function __construct($dataGateway, ResultSetInterface $resultSetPrototype)
    {
        $this->setDataGateway($dataGateway);
        $this->setResultSetPrototype($resultSetPrototype);
    }
}
