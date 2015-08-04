<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Facade;

use Matryoshka\Model\ModelLocatorInterface;
use Matryoshka\Model\Criteria\CriteriaManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Matryoshka\Model\Facade\Chain\FindChain;
use Matryoshka\Model\ModelInterface;
use Matryoshka\Model\Criteria\CriteriaInterface;

/**
 * Class Facade
 */
class Facade implements ModelLocatorInterface
{

    /**
     * @var ModelLocatorInterface
     */
    protected $modelLocator;

    /**
     * @var CriteriaManager
     */
    protected $criteriaLocator;

    public function __construct(
        ModelLocatorInterface $modelLocator,
        CriteriaManager $criteriaLocator
    ) {
        $this->modelLocator = $modelLocator;
        $this->criteriaLocator = $criteriaLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return $this->modelLocator->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return $this->modelLocator->has($name);
    }

    /**
     *
     * @param string $name
     * @param array $params
     * @param array $options
     * @return CriteriaInterface
     */
    public function createCriteria($name, array $params = null, array $options = [])
    {
         $criteria = $this->criteriaLocator->get($name, $options);
         if (!empty($params)) {
             $hydrator = new ClassMethods(true);
             $hydrator->hydrate($params, $criteria);
         }
         return $criteria;
    }

    /**
     * @param string|ModelInterface $model
     * @param null|string|CriteriaInterface $withCriteria
     * @param null|array $withCriteriaParams
     * @return FindChain
     */
    public function findWithin($model, $withCriteria = null, array $withCriteriaParams = null)
    {
        $chain = new FindChain($this);
        $chain->within($model);

        if ($withCriteria) {
            $chain->with($criteria, $withCriteriaParams);
        }

        return $chain;
    }





}