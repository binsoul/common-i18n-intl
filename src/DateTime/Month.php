<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use IntlCalendar;

class Month extends Point
{
    /**
     * @var Year
     */
    private $year;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(int $number, Year $year, PointFactory $periodFactory, IntlDateTimeFormatter $formatter)
    {
        parent::__construct($number, $periodFactory, $formatter);

        $this->year = $year;
    }

    public function getNumber(): int
    {
        // ICU month is zero based
        return $this->number + 1;
    }

    public function getDateShort(): string
    {
        return $this->format('M');
    }

    public function getDateMedium(): string
    {
        return $this->format('MM');
    }

    public function getDateLong(): string
    {
        return $this->format('MM YYYY');
    }

    public function getDateIso(): string
    {
        return $this->formatter->formatObject($this->getDateTime(), 'yyyy-MM', 'en');
    }

    public function getNameShort(): string
    {
        return $this->format('LLLLL');
    }

    public function getNameMedium(): string
    {
        return $this->format('LLL');
    }

    public function getNameLong(): string
    {
        return $this->format('LLLL');
    }

    /**
     * Returns the month after this month.
     */
    public function getNextMonth(): self
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_MONTH, 1);

        return $this->factory->getMonth($calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));
    }

    /**
     * Returns the month before this month.
     */
    public function getPreviousMonth(): self
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_MONTH, -1);

        return $this->factory->getMonth($calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));
    }

    /**
     * Returns the first day of the month.
     */
    public function getFirstDay(): Day
    {
        $calendar = $this->prepareCalendar();

        $day = $calendar->getActualMinimum(IntlCalendar::FIELD_DATE);

        return $this->factory->getDay($day, $this->number, $this->year->getNumber());
    }

    /**
     * Returns the last day of the month.
     */
    public function getLastDay(): Day
    {
        $calendar = $this->prepareCalendar();

        $day = $calendar->getActualMaximum(IntlCalendar::FIELD_DATE);

        return $this->factory->getDay($day, $this->number, $this->year->getNumber());
    }

    /**
     * Returns all days of the month.
     *
     * @return Day[]
     */
    public function getDays(): array
    {
        $calendar = $this->prepareCalendar();

        $result = [];
        $firstDay = $calendar->getActualMinimum(IntlCalendar::FIELD_DATE);
        $lastDay = $calendar->getActualMaximum(IntlCalendar::FIELD_DATE);

        for ($day = $firstDay; $day <= $lastDay; $day++) {
            $result[] = $this->factory->getDay($day, $this->number, $this->year->getNumber());
        }

        return $result;
    }

    /**
     * Returns all weeks of the month.
     *
     * @return Week[]
     */
    public function getWeeks(): array
    {
        $calendar = $this->prepareCalendar();
        $lastDay = $calendar->getActualMaximum(IntlCalendar::FIELD_DATE);
        $firstMonth = $calendar->getActualMinimum(IntlCalendar::FIELD_MONTH);
        $lastWeek = $calendar->getActualMaximum(IntlCalendar::FIELD_WEEK_OF_MONTH);

        $dayOfWeek = $calendar->get(IntlCalendar::FIELD_DOW_LOCAL);
        $calendar->add(IntlCalendar::FIELD_DATE, -($dayOfWeek - 1));

        $year = $this->year->getNumber();
        $previousWeek = -1;

        $result = [];

        while (true) {
            if ($this->isEndOfWeek($calendar)) {
                $currentWeek = $calendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR);

                if ($this->number === $firstMonth && $currentWeek > $lastWeek) {
                    $year--;
                }

                if ($previousWeek > $currentWeek) {
                    $year++;
                }

                $previousWeek = $currentWeek;

                $result[] = $this->factory->getWeek($currentWeek, $year);

                if ($calendar->get(IntlCalendar::FIELD_MONTH) !== $this->number || $calendar->get(IntlCalendar::FIELD_DATE) === $lastDay) {
                    break;
                }
            }

            $calendar->add(IntlCalendar::FIELD_DATE, 1);
        }

        return $result;
    }

    /**
     * Returns the year of the month.
     */
    public function getYear(): Year
    {
        return $this->year;
    }

    protected function prepareCalendar(): IntlCalendar
    {
        $calendar = $this->factory->getCalendar();

        $calendar->set($this->year->getNumber(), $this->number, 1, 0, 0, 0);
        $calendar->set(IntlCalendar::FIELD_DATE, $calendar->getActualMinimum(IntlCalendar::FIELD_DATE));

        return $calendar;
    }

    /**
     * Checks if the date is the local end of the week.
     */
    private function isEndOfWeek(IntlCalendar $c): bool
    {
        return $c->get(IntlCalendar::FIELD_DOW_LOCAL) === 7;
    }
}
