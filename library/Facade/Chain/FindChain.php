<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Facade\Chain;


use Matryoshka\Model\Facade\Facade;
use Matryoshka\Model\Criteria\ReadableCriteriaInterface;
use Matryoshka\Model\Exception\InvalidArgumentException;
use Matryoshka\Model\ModelInterface;
use Matryoshka\Model\ResultSet\ResultSetInterface;

/**
 * Class FindChain
 */
class FindChain
{
    use ConstructorTrait;
    use WithTrait;
    use WithinTrait;

    /**
     * @return ResultSetInterface
     */
    public function getResultSet()
    {
        return $this->retrieveModel($this)->find(
            $this->retrieveCriteria($facade, ReadableCriteriaInterface::class)
        );
    }

    /**
     * @return object|null
     */
    public function getCurrent()
    {
        return $this->getResultSet()->current();
    }

}