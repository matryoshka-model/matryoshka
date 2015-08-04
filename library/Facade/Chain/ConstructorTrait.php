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

/**
 * Trait ConstructorTrait
 */
trait ConstructorTrait
{
    /**
     * @var Facade
     */
    protected $facade;

    public function __construct(Facade $facade)
    {
        $this->facade = $facade;
    }
}