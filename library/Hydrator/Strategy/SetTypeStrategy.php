<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Class SetTypeStrategy
 */
class SetTypeStrategy implements StrategyInterface, NullableStrategyInterface
{
    use NullableStrategyTrait;

    /**
     * Type to extract to
     *
     * @var string
     */
    protected $extractToType;

    /**
     * Type to hydrate to
     *
     * @var string
     */
    protected $hydrateToType;

    /**
     * Ctor
     *
     * @param $extractToType
     * @param $hydrateToType
     */
    public function __construct($extractToType, $hydrateToType)
    {
        $this->extractToType = $extractToType;
        $this->hydrateToType = $hydrateToType;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if ($this->nullable && $value === null) {
            return null;
        }
        settype($value, $this->extractToType);
        return $value;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if ($this->nullable && $value === null) {
            return null;
        }
        settype($value, $this->hydrateToType);
        return $value;
    }
}
