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
 * Interface ModelLocatorInterface
 *
 * A dedicated service locator for your model service classes.
 */
interface ModelLocatorInterface
{

    /**
     *
     * @param  string|array $name
     * @return bool
     */
    public function has($name);

    /**
     * Get a model
     *
     * @param  string $name
     * @return ModelInterface
     */
    public function get($name);
}
