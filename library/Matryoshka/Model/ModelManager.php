<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

use Zend\ServiceManager\AbstractPluginManager;

class ModelManager extends AbstractPluginManager
{
    /**
     * Share by default
     *
     * @var bool
    */
    protected $shareByDefault = true;

    /**
     * Validate the plugin
     *
     * Checks that the model loaded is an instance of ModelInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof ModelInterface) {
            // we're okay
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s\ModelInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}