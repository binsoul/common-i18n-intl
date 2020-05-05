<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use IntlCalendar;

class Day extends Point
{
    /**
     * @var Month
     */
    private $month;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(int $number, Month $month, PointFactory $periodFactory, IntlDateTimeFormatter $formatter)
    {
        parent::__construct($number, $periodFactory, $formatter);

        $this->month = $month;
    }

    public function getDateShort(): string
    {
        return $this->format('d');
    }

    public function getDateMedium(): string
    {
        return $this->format('dd');
    }

    public function getDateLong(): string
    {
        return $this->formatter->formatDate($this->getDateTime());
    }

    public function getDateIso(): string
    {
        return $this->formatter->formatPattern($this->getDateTime(), IntlDateTimeFormatter::ISO8601_DATE);
    }

    public function getNameShort(): string
    {
        return $this->format('ccccc');
    }

    public function getNameMedium(): string
    {
        return $this->format('ccc');
    }

    public function getNameLong(): string
    {
        return $this->format('cccc');
    }

    /**
     * Returns true if the day is a weekday.
     */
    public function isWeekday(): bool
    {
        $calendar = $this->prepareCalendar();

        return ! $calendar->isWeekend();
    }

    /**
     * Returns true if the day is on a weekend.
     */
    public function isWeekend(): bool
    {
        $calendar = $this->prepareCalendar();

        return $calendar->isWeekend();
    }

    /**
     * Returns true if the day the same a another day.
     */
    public function isSameDay(self $day): bool
    {
        return $this->getNumber() === $day->getNumber() && $this->isSameMonth($day->getMonth());
    }

    /**
     * Returns true if the day is in the same month.
     */
    public function isSameMonth(Month $month): bool
    {
        return $this->month->getNumber() === $month->getNumber() && $this->isSameYear($month->getYear());
    }

    /**
     * Returns true if the day is in the same year.
     */
    public function isSameYear(Year $year): bool
    {
        return $this->month->getYear()->getNumber() === $year->getNumber();
    }

    /**
     * Returns the day after this day.
     */
    public function getNextDay(): self
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_DATE, 1);

        return $this->factory->getDay($calendar->get(IntlCalendar::FIELD_DATE), $calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));
    }

    /**
     * Returns the day before this day.
     */
    public function getPreviousDay(): self
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_DATE, -1);

        return $this->factory->getDay($calendar->get(IntlCalendar::FIELD_DATE), $calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));
    }

    /**
     * Returns the week of the day.
     */
    public function getWeek(): Week
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_DATE, -7);
        $previousWeek = $calendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR);

        $calendar = $this->prepareCalendar();
        $firstMonth = $calendar->getActualMinimum(IntlCalendar::FIELD_MONTH);
        $lastWeekOfMonth = $calendar->getActualMaximum(IntlCalendar::FIELD_WEEK_OF_MONTH);

        $year = $this->getYear()->getNumber();

        $currentWeek = $calendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR);

        if ($currentWeek > $lastWeekOfMonth && $this->month->getNumber() - 1 === $firstMonth) {
            $year--;
        }

        if ($previousWeek > $currentWeek) {
            $year++;
        }

        return $this->factory->getWeek($calendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR), $year);
    }

    /**
     * Returns the month of the day.
     */
    public function getMonth(): Month
    {
        return $this->month;
    }

    /**
     * Returns the year of the day.
     */
    public function getYear(): Year
    {
        return $this->month->getYear();
    }

    protected function prepareCalendar(): IntlCalendar
    {
        $calendar = $this->factory->getCalendar();

        $calendar->set($this->month->getYear()->getNumber(), $this->month->getNumber() - 1, $this->number, 0, 0, 0);

        return $calendar;
    }
}
