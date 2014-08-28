<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\TestAsset;

use Matryoshka\Model\AbstractModel;
use Matryoshka\Model\ResultSet\ResultSetInterface;
class ConcreteAbstractModel extends AbstractModel
{
    public function setResultSetPrototype(ResultSetInterface $resultSet)
    {
        return parent::setResultSetPrototype($resultSet);
    }
}