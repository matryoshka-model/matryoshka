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

class Model extends AbstractModel
{
    public function __construct($dataGataway, CriteriaInterface $defaultCriteria, ResultSetInterface $resultSetPrototype = null)
    {
        $this->dataGateway      = $dataGataway;
        $this->defaultCriteria  = $defaultCriteria;

        if (null === $resultSetPrototype) {
            if (method_exists($dataGataway, 'getResultSetPrototype')) {
                $resultSetPrototype = $dataGataway->getResultSetPrototype();
            }
        }
        if ($resultSetPrototype instanceof ResultSetInterface) {
            $this->resultSetPrototype = $resultSetPrototype;
        } else {
            throw Exception\UnexpectedValueException('$resultSetPrototype must be an instace of Matryoshka\Model\ResultSet\ResultSetInterface');
        }
    }
}