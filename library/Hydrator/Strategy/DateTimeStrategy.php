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
     * @return DateTime|null
     */
    public function hydrate($value)
    {
        if ($this->nullable && $value === null) {
            return null;
        }

        if (is_string($value)) {
            if ($dateTime = DateTime::createFromFormat($this->getFormat(), $value)) {
                return $dateTime;
            }

            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid value: must be a string representing the time according to DateTime::createFromFormat(), "%s" given.',
                $value
            ));
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Invalid value: must be a string representing the time, "%s" given',
            is_object($value) ? get_class($value) : gettype($value)
        ));

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

        if ($this->nullable && $value === null) {
            return null;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Invalid value: must be an instance of DateTime, "%s" given.',
            is_object($value) ? get_class($value) : gettype($value)
        ));
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
