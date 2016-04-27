<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;

/**
 * Class Model
 *
 * Default concrete implementation of {@link AbstractModel}.
 */
class Model extends AbstractModel
{
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
