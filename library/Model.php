<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ResultSet\ResultSetInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class Model
 *
 * Default concrete implementation of {@link AbstractModel}.
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
