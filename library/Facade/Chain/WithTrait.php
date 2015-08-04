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
use Matryoshka\Model\Criteria\CriteriaInterface;
use Matryoshka\Model\Exception\UnexpectedValueException;
use Matryoshka\Model\Exception\RuntimeException;

/**
 * Trait WithTrait
 */
trait WithTrait
{

    /**
     * @var string|CriteriaInterface
     */
    protected $with;

    /**
     * @var array
     */
    protected $withParams;

    /**
     *
     * @param string|CriteriaInterface $criteria
     * @param array $criteriaParams
     * @return $this
     */
    public function with($criteria, array $criteriaParams = null)
    {
        $this->with = $criteria;
        $this->withParams = $criteriaParams;
        return $this;
    }

    /**
     *
     * @param Facade $facade
     * @param string $expectedInstanceOf
     * @return CriteriaInterface
     * @throws RuntimeException
     * @throws UnexpectedValueException
     */
    protected function retrieveCriteria(Facade $facade, $expectedInstanceOf = CriteriaInterface::class)
    {
        if (!$this->with) {
            throw new RuntimeException('with() must be called prior');
        }

        $criteria = $facade->createCriteria($this->with, $this->withParams);
        if (!$criteria instanceof $expectedInstanceOf) {
            throw new UnexpectedValueException(sprintf(
                'Expected an instance of "%s", "%s" retrieved',
                $expectedInstanceOf,
                get_class($criteria)
            ));
        }

        return $criteria;
    }
}