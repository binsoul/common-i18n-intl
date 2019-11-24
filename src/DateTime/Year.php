<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use IntlCalendar;

class Year extends Point
{
    public function getDateShort(): string
    {
        return $this->format('yy');
    }

    public function getDateMedium(): string
    {
        return $this->format('yyyy');
    }

    public function getDateLong(): string
    {
        return $this->format('yyyy');
    }

    public function getDateIso(): string
    {
        return $this->formatter->formatObject($this->getDateTime(), 'yyyy', 'en');
    }

    public function getNameShort(): string
    {
        return $this->format('yy');
    }

    public function getNameMedium(): string
    {
        return $this->format('yyyy');
    }

    public function getNameLong(): string
    {
        return $this->format('yyyy');
    }

    /**
     * Returns all months of the year.
     *
     * @return Month[]
     */
    public function getMonths(): array
    {
        $calendar = $this->prepareCalendar();
        $calendar->set($this->number, 0, 1, 0, 0, 0);

        $result = [];
        $firstMonth = $calendar->getActualMinimum(IntlCalendar::FIELD_MONTH);
        $lastMonth = $calendar->getActualMaximum(IntlCalendar::FIELD_MONTH);
        for ($month = $firstMonth; $month <= $lastMonth; ++$month) {
            $result[] = $this->factory->getMonth($month, $this->number);
        }

        return $result;
    }

    /**
     * Returns all weeks of the year.
     *
     * @return Week[]
     */
    public function getWeeks(): array
    {
        $calendar = $this->prepareCalendar();
        $calendar->set($this->number, 0, 1, 0, 0, 0);

        $result = [];
        $firstWeek = $calendar->getActualMinimum(IntlCalendar::FIELD_WEEK_OF_YEAR);
        $lastWeek = $calendar->getActualMaximum(IntlCalendar::FIELD_WEEK_OF_YEAR);
        for ($week = $firstWeek; $week <= $lastWeek; ++$week) {
            $result[] = $this->factory->getWeek($week, $this->number);
        }

        return $result;
    }

    /**
     * Returns all days of the year.
     *
     * @return Day[]
     */
    public function getDays(): array
    {
        $result = [];
        foreach ($this->getMonths() as $month) {
            foreach ($month->getDays() as $day) {
                $result[] = $day;
            }
        }

        return $result;
    }

    /**
     * Returns the first month of the year.
     */
    public function getFirstMonth(): Month
    {
        $calendar = $this->prepareCalendar();

        $month = $calendar->getActualMinimum(IntlCalendar::FIELD_MONTH);

        return $this->factory->getMonth($month, $this->number);
    }

    /**
     * Returns the last month of the year.
     */
    public function getLastMonth(): Month
    {
        $calendar = $this->prepareCalendar();

        $month = $calendar->getActualMaximum(IntlCalendar::FIELD_MONTH);

        return $this->factory->getMonth($month, $this->number);
    }

    /**
     * Returns the first week of the year.
     */
    public function getFirstWeek(): Week
    {
        $calendar = $this->prepareCalendar();

        $week = $calendar->getActualMinimum(IntlCalendar::FIELD_WEEK_OF_YEAR);

        return $this->factory->getWeek($week, $this->number);
    }

    /**
     * Returns the last week of the year.
     */
    public function getLastWeek(): Week
    {
        $calendar = $this->prepareCalendar();

        $week = $calendar->getActualMaximum(IntlCalendar::FIELD_WEEK_OF_YEAR);

        return $this->factory->getWeek($week, $this->number);
    }

    /**
     * Returns the first day of the year.
     */
    public function getFirstDay(): Day
    {
        return $this->getFirstMonth()->getFirstDay();
    }

    /**
     * Returns the last day of the year.
     */
    public function getLastDay(): Day
    {
        return $this->getLastMonth()->getLastDay();
    }

    protected function prepareCalendar(): IntlCalendar
    {
        $calendar = $this->factory->getCalendar();

        $calendar->set($this->number, 0, 1, 0, 0, 0);

        $calendar->set(IntlCalendar::FIELD_MONTH, $calendar->getActualMinimum(IntlCalendar::FIELD_MONTH));
        $calendar->set(IntlCalendar::FIELD_DATE, $calendar->getActualMinimum(IntlCalendar::FIELD_DATE));

        return $calendar;
    }
}
