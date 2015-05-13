<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

/**
 * Trait ModelAwareTrait
 */
trait ModelAwareTrait
{
    /**
     * Model
     * @var ModelInterface
     */
    protected $model;

    /**
     * Set Model
     * @param ModelInterface $model
     * @return $this
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get Model
     * @return ModelInterface
     */
    public function getModel()
    {
        return $this->model;
    }
}
