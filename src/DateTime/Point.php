<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use DateTime;
use IntlCalendar;

/**
 * Represents a point in time.
 */
abstract class Point
{
    /**
     * @var int
     */
    protected $number;
    /**
     * @var PointFactory
     */
    protected $factory;
    /**
     * @var IntlDateTimeFormatter
     */
    protected $formatter;
    /**
     * @var PropertyBag
     */
    private $properties;

    /**
     * Constructs an instance of this class.
     *
     * @param int                   $number
     * @param PointFactory          $factory
     * @param IntlDateTimeFormatter $formatter
     */
    public function __construct(int $number, PointFactory $factory, IntlDateTimeFormatter $formatter)
    {
        $this->number = $number;
        $this->factory = $factory;
        $this->formatter = $formatter;
    }

    /**
     * Returns the number associated with this point.
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Returns the DateTime of this point.
     *
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        $calendar = $this->prepareCalendar();

        return $calendar->toDateTime();
    }

    /**
     * Formats the point using the given pattern.
     *
     * @param string $pattern
     *
     * @return string
     */
    public function format(string $pattern): string
    {
        $calendar = $this->prepareCalendar();

        return $this->formatter->formatObject($calendar, $pattern);
    }

    /**
     * Returns the PropertyBag for this point.
     *
     * @return PropertyBag
     */
    public function getProperties(): PropertyBag
    {
        if ($this->properties === null) {
            $this->properties = new PropertyBag();
        }

        return $this->properties;
    }

    /**
     * Initializes a calendar.
     *
     * @return IntlCalendar
     */
    abstract protected function prepareCalendar(): IntlCalendar;
}
