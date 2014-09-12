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
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateTimeStrategy extends DefaultStrategy
{
    /**
     * @var string
     */
    protected $format;

    public function __construct($format = null)
    {
        $this->setFormat(DateTime::ISO8601);
        if($format !== null) {
            $this->setFormat($format);
        }
    }

    /**
     * {@inheritdoc}
     *
     * Convert a string value into a DateTime object
     * @return DateTime
     */
    public function hydrate($value)
    {
        if (is_string($value)) {

            $value = new DateTime($value);
        }

        return $value;
    }

    public function extract($value)
    {
        if ($value instanceof DateTime) {

            $value = $value->format($this->getFormat());
        }
        return $value;
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
