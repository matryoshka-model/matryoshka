<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;

abstract class AbstractCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    abstract public function apply( ModelInterface $model );
}