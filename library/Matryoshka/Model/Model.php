<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Matryoshka\Model\ResultSet\ResultSetInterface;
use Matryoshka\Model\Exception;
use Matryoshka\Model\Criteria\CriteriaInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;


class Model extends AbstractModel
{
    /**
     * @param $dataGataway
     * @param ResultSetInterface $resultSetPrototype
     */
    public function __construct($dataGataway, ResultSetInterface $resultSetPrototype, HydratorInterface $hydrator = null)
    {
        $this->dataGateway      = $dataGataway;
        if($hydrator) {
            $this->setHydrator($hydrator);
        }

        $this->setResultSetPrototype($resultSetPrototype);
    }
}