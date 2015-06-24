<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Hydrator\Strategy;

use DateTime;
use Matryoshka\Model\Exception;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Class DateTimeStrategy
 */
class DateTimeStrategy implements StrategyInterface, NullableStrategyInterface
{
    use NullableStrategyTrait;

    /**
     * @var string
     */
    protected $format = DateTime::ISO8601;

    /**
     * @param string|null $format
     */
    public function __construct($format = null)
    {
        if ($format !== null) {
            $this->setFormat($format);
        }
    }

    /**
     * {@inheritdoc}
     * Convert a string value into a DateTime object
     *
     * @param string|int|null $value
     * @return \DateTime|null
     */
    public function hydrate($value)
    {
        if ($this->nullable && $value === null) {
            return null;
        }

        if (is_string($value) || is_int($value)) {
            if ($dateTime = DateTime::createFromFormat($this->getFormat(), $value)) {
                return $dateTime;
            }

            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid format or value: format must be a string representing a valid \DateTime format, "%s" given;' .
                'value must be a string representing the time according to \DateTime::createFromFormat(), "%s" given.',
                $this->getFormat(),
                $value
            ));
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Invalid value: must be a string or integer representing the time according to the format, "%s" given',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    /**
     * {@inheritdoc}
     * Convert a DateTime object into a string
     *
     * @param \DateTime|null $value
     * @return string|null
     */
    public function extract($value)
    {
        if ($value instanceof DateTime) {
            return $value->format($this->getFormat());
        }

        if ($this->nullable && $value === null) {
            return null;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Invalid value: must be an instance of DateTime, "%s" given.',
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    /**
     * Set format
     *
     * @param string $format
     * @return DateTimeStrategy
     */
    public function setFormat($format)
    {
        $this->format = (string) $format;
        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }
}
