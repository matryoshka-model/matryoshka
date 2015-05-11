<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Class DateTimeStrategy
 */
class DateTimeStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    protected $format = DateTime::ISO8601;

    /**
     * @param string|null $format
     */
    public function __construct($format = null)
    {
        if($format !== null) {
            $this->setFormat($format);
        }
    }

    /**
     * {@inheritdoc}
     * Convert a string value into a DateTime object
     *
     * @return DateTime|null
     */
    public function hydrate($value)
    {
        if (is_string($value)) {
            $value = DateTime::createFromFormat($this->getFormat(), $value);
            if ($value) {
                return $value;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     * Convert a DateTime object into a string
     *
     * @return string|null
     */
    public function extract($value)
    {
        if ($value instanceof DateTime) {
            return $value->format($this->getFormat());
        }
        return null;
    }

    /**
     * @param string $format
     * @return DateTimeStrategy
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
}
