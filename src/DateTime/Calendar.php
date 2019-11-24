<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\DefaultTimeZone;
use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use BinSoul\Common\I18n\Locale;
use BinSoul\Common\I18n\TimeZone;
use DateInterval;
use DateTime;
use DateTimeInterface;
use IntlCalendar;

/**
 * Represents a calendar for a time zone and a locale.
 */
class Calendar
{
    /**
     * @var IntlDateTimeFormatter
     */
    private $formatter;
    /**
     * @var PointFactory
     */
    private $factory;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?Locale $locale = null, ?TimeZone $timeZone = null)
    {
        if ($locale === null) {
            $locale = DefaultLocale::fromString(\Locale::getDefault());
        }

        if ($timeZone === null) {
            $timeZone = new DefaultTimeZone(\IntlTimeZone::createDefault()->toDateTimeZone()->getName());
        }

        $this->formatter = new IntlDateTimeFormatter($locale);
        $this->factory = new PointFactory(IntlCalendar::createInstance($timeZone->getName(), $locale->getCode()), $this->formatter);
    }

    /**
     * Returns a year.
     */
    public function getYear(int $year): Year
    {
        return $this->factory->getYear($year);
    }

    /**
     * Returns one month of a year.
     */
    public function getMonth(int $month, int $year): Month
    {
        return $this->factory->getMonth($month - 1, $year);
    }

    /**
     * Returns one week of a year.
     */
    public function getWeek(int $week, int $year): Week
    {
        return $this->factory->getWeek($week, $year);
    }

    /**
     * Returns one day of a year.
     */
    public function getDay(int $day, int $month, int $year): Day
    {
        return $this->factory->getDay($day, $month - 1, $year);
    }

    /**
     * Returns a day for the given DateTime object.
     */
    public function fromDate(DateTimeInterface $dateTime): Day
    {
        return $this->getDay((int) $dateTime->format('d'), (int) $dateTime->format('m'), (int) $dateTime->format('Y'));
    }

    /**
     * Returns the day before today.
     */
    public function yesterday(): Day
    {
        return $this->fromDate((new DateTime())->sub(new DateInterval('P1D')));
    }

    /**
     * Returns the current day.
     */
    public function today(): Day
    {
        return $this->fromDate(new DateTime());
    }

    /**
     * Returns the day after today.
     */
    public function tomorrow(): Day
    {
        return $this->fromDate((new DateTime())->add(new DateInterval('P1D')));
    }

    /**
     * Returns the week before the current week.
     */
    public function lastWeek(): Week
    {
        return $this->thisWeek()->getPreviousWeek();
    }

    /**
     * Returns the current week.
     */
    public function thisWeek(): Week
    {
        return $this->today()->getWeek();
    }

    /**
     * Returns the week after the current week.
     */
    public function nextWeek(): Week
    {
        return $this->thisWeek()->getNextWeek();
    }

    /**
     * Returns the names of the weekdays starting with monday.
     *
     * @return string[]
     */
    public function getDayOfWeekNames(): array
    {
        $day = new DateTime('next Monday');
        $result = [];
        for ($i = 0; $i < 7; ++$i) {
            $result[] = $this->formatter->formatObject($day, 'cccc');
            $day->add(new DateInterval('P1D'));
        }

        return $result;
    }
}
