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
use Matryoshka\Model\Exception\UnexpectedValueException;
use Matryoshka\Model\Exception\RuntimeException;
use Matryoshka\Model\ModelInterface;

/**
 * Trait WithinTrait
 */
trait WithinTrait
{

    /**
     * @var string|ModelInterface
     */
    protected $within;

    /**
     *
     * @param string|ModelInterface $model
     * @return $this
     */
    public function within($model)
    {
        $this->within = $model;
        return $this;
    }

    /**
     *
     * @param Facade $facade
     * @param string $expectedInstanceOf
     * @return ModelInterface
     * @throws RuntimeException
     * @throws UnexpectedValueException
     */
    protected function retrieveModel(Facade $facade, $expectedInstanceOf = ModelInterface::class)
    {
        if (!$this->within) {
            throw new RuntimeException('within() must be called prior');
        }

        $model = $facade->get($this->within);
        if (!$model instanceof $expectedInstanceOf) {
            throw new UnexpectedValueException(sprintf(
                'Expected an instance of "%s", "%s" retrivied',
                $expectedInstanceOf,
                get_class($model)
            ));
        }

        return $model;
    }
}