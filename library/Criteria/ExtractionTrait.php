<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Criteria;

use Matryoshka\Model\Exception;
use Matryoshka\Model\ModelStubInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;

/**
 * Trait ExtractionTrait
 *
 * This trait contains utility methods in order to extract values or names from
 * the object point of view to the datagateway one.
 */
trait ExtractionTrait
{

    /**
     * Extract a value in order to be used within datagateway context
     *
     * If $extractName is false, $name must be in the datagateway context,
     * otherwise $name will be converted using extractName().
     *
     * @param ModelStubInterface $model
     * @param string $name
     * @param string $value
     * @param bool $extractName
     * @throws Exception\RuntimeException
     */
    protected function extractValue(ModelStubInterface $model, $name, $value, $extractName = true)
    {
        $modelHydrator = $model->getHydrator();
        if (!$modelHydrator || !method_exists($modelHydrator, 'extractValue')) {
            throw new Exception\RuntimeException(
                'Model hydrator must be set and must have extractValue() method ' .
                'in order extract a single value'
                );
        }

        if ($extractName) {
            $name = $this->extractName($model, $name);
        }

        return $modelHydrator->extractValue($name, $value);
    }

    /**
     * Extract a name in order to be used within datagateway context
     *
     * If an object's hydrator is avaliable, then $name will be converted to
     * a model name using the object's hydrator naming strategy.
     * Finally, $name will be extracted using the model's hydrator naming
     * strategy.
     *
     * @param ModelStubInterface $model
     * @param string $name
     * @throws Exception\RuntimeException
     * @return string
     */
    protected function extractName(ModelStubInterface $model, $name)
    {
        if ($model->getObjectPrototype() instanceof HydratorAwareInterface) {
            $objectHydrator = $model->getObjectPrototype()->getHydrator();
            
            if (!$objectHydrator || !method_exists($objectHydrator, 'hydrateName')) {
                throw new Exception\RuntimeException(
                    'Object hydrator must be set and must have hydrateName() ' .
                    'in order to convert a single field'
                    );
            }
            $name = $objectHydrator->hydrateName($name);
        }

        $modelHydrator = $model->getHydrator();
        if (!$modelHydrator || !method_exists($modelHydrator, 'extractName')) {
            throw new Exception\RuntimeException(
                'Model hydrator must be set and must have extractName() method ' .
                'in order to convert a single field'
                );
        }
        
        return $modelHydrator->extractName($name);
    }
}
