<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use IntlCalendar;
use Locale;

/**
 * Generates points in time.
 */
class PointFactory
{
    private IntlCalendar $internalCalendar;

    /**
     * @var Year[]
     */
    private array $years = [];

    /**
     * @var Month[]
     */
    private array $months = [];

    /**
     * @var Week[]
     */
    private array $weeks = [];

    /**
     * @var Day[]
     */
    private array $days = [];

    /**
     * Constructs an instance of this class.
     */
    public function __construct(
        private readonly IntlCalendar $calendar,
        private readonly IntlDateTimeFormatter $formatter
    ) {
        $this->internalCalendar = IntlCalendar::createInstance($this->calendar->getTimeZone(), $this->calendar->getLocale(Locale::VALID_LOCALE));
    }

    /**
     * Returns the calendar.
     */
    public function getCalendar(): IntlCalendar
    {
        return $this->calendar;
    }

    /**
     * Returns a year.
     */
    public function getYear(int $year): Year
    {
        if (! isset($this->years[$year])) {
            $this->years[$year] = new Year($year, $this, $this->formatter);
        }

        return $this->years[$year];
    }

    /**
     * Returns a month.
     */
    public function getMonth(int $month, int $year): Month
    {
        $this->internalCalendar->set($year, $month, 1, 0, 0, 0);
        $year = $this->internalCalendar->get(IntlCalendar::FIELD_YEAR);
        $month = $this->internalCalendar->get(IntlCalendar::FIELD_MONTH);

        $index = $year . '-' . $month;

        if (! isset($this->months[$index])) {
            $this->months[$index] = new Month($month, $this->getYear($year), $this, $this->formatter);
        }

        return $this->months[$index];
    }

    /**
     * Returns a week.
     */
    public function getWeek(int $week, int $year): Week
    {
        $this->internalCalendar->set($year, 0, 1, 0, 0, 0);
        $this->internalCalendar->set(IntlCalendar::FIELD_WEEK_OF_YEAR, $week);
        $this->internalCalendar->set(IntlCalendar::FIELD_DOW_LOCAL, 1);

        $actualWeek = $this->internalCalendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR);

        if ($actualWeek != $week) {
            $year = $this->internalCalendar->get(IntlCalendar::FIELD_YEAR);
            $week = $actualWeek;
        }

        $index = $year . '-' . $week;

        if (! isset($this->weeks[$index])) {
            $this->weeks[$index] = new Week($week, $this->getYear($year), $this, $this->formatter);
        }

        return $this->weeks[$index];
    }

    /**
     * Returns a day.
     */
    public function getDay(int $day, int $month, int $year): Day
    {
        $this->internalCalendar->set($year, $month, $day, 0, 0, 0);
        $year = $this->internalCalendar->get(IntlCalendar::FIELD_YEAR);
        $month = $this->internalCalendar->get(IntlCalendar::FIELD_MONTH);
        $day = $this->internalCalendar->get(IntlCalendar::FIELD_DATE);

        $index = $year . '-' . $month . '-' . $day;

        if (! isset($this->days[$index])) {
            $this->days[$index] = new Day($day, $this->getMonth($month, $year), $this, $this->formatter);
        }

        return $this->days[$index];
    }
}
