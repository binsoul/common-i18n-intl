<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\DefaultTimeZone;
use BinSoul\Common\I18n\Locale;
use BinSoul\Common\I18n\TimeZone;
use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
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
     *
     * @param Locale|null   $locale
     * @param TimeZone|null $timeZone
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
     *
     * @param int $year
     *
     * @return Year
     */
    public function getYear(int $year): Year
    {
        return $this->factory->getYear($year);
    }

    /**
     * Returns one month of a year.
     *
     * @param int $month
     * @param int $year
     *
     * @return Month
     */
    public function getMonth(int $month, int $year): Month
    {
        return $this->factory->getMonth($month - 1, $year);
    }

    /**
     * Returns one week of a year.
     *
     * @param int $week
     * @param int $year
     *
     * @return Week
     */
    public function getWeek(int $week, int $year): Week
    {
        return $this->factory->getWeek($week, $year);
    }

    /**
     * Returns one day of a year.
     *
     * @param int $day
     * @param int $month
     * @param int $year
     *
     * @return Day
     */
    public function getDay(int $day, int $month, int $year): Day
    {
        return $this->factory->getDay($day, $month - 1, $year);
    }

    /**
     * Returns a day for the given DateTime object.
     *
     * @param DateTimeInterface $dateTime
     *
     * @return Day
     */
    public function fromDate(DateTimeInterface $dateTime): Day
    {
        return $this->getDay((int) $dateTime->format('d'), (int) $dateTime->format('m'), (int) $dateTime->format('Y'));
    }

    /**
     * Returns the day before today.
     *
     * @return Day
     */
    public function yesterday(): Day
    {
        return $this->fromDate((new DateTime())->sub(new DateInterval('P1D')));
    }

    /**
     * Returns the current day.
     *
     * @return Day
     */
    public function today(): Day
    {
        return $this->fromDate(new DateTime());
    }

    /**
     * Returns the day after today.
     *
     * @return Day
     */
    public function tomorrow(): Day
    {
        return $this->fromDate((new DateTime())->add(new DateInterval('P1D')));
    }

    /**
     * Returns the week before the current week.
     *
     * @return Week
     */
    public function lastWeek(): Week
    {
        return $this->thisWeek()->getPreviousWeek();
    }

    /**
     * Returns the current week.
     *
     * @return Week
     */
    public function thisWeek(): Week
    {
        return $this->today()->getWeek();
    }

    /**
     * Returns the week after the current week.
     *
     * @return Week
     */
    public function nextWeek(): Week
    {
        return $this->thisWeek()->getNextWeek();
    }

    /**
     * Returns the names of the weekdays starting with monday.
     *
     * @return array
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
