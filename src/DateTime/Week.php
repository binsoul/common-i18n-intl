<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use IntlCalendar;

class Week extends Point
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

    public function getDateShort(): string
    {
        return $this->format('w');
    }

    public function getDateMedium(): string
    {
        return $this->format('ww');
    }

    public function getDateLong(): string
    {
        $calendar = $this->prepareCalendar();

        if ($calendar->get(IntlCalendar::FIELD_YEAR) !== $this->year->getNumber()) {
            $calendar->set(IntlCalendar::FIELD_YEAR, $this->year->getNumber());
        }

        return $this->formatter->formatObject($calendar, 'ww yyyy');
    }

    public function getNameShort(): string
    {
        return $this->format('w');
    }

    public function getNameMedium(): string
    {
        return $this->format('ww');
    }

    public function getNameLong(): string
    {
        return $this->format('ww');
    }

    /**
     * Returns all days of the week.
     *
     * @return Day[]
     */
    public function getDays(): array
    {
        $calendar = $this->prepareCalendar();

        $result = [];

        while (! $this->isEndOfWeek($calendar)) {
            $result[] = $this->factory->getDay($calendar->get(IntlCalendar::FIELD_DATE), $calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));

            $calendar->add(IntlCalendar::FIELD_DATE, 1);
        }

        $result[] = $this->factory->getDay($calendar->get(IntlCalendar::FIELD_DATE), $calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));

        return $result;
    }

    /**
     * Returns the week after this week.
     */
    public function getNextWeek(): self
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_WEEK_OF_YEAR, 1);

        $nextWeek = $calendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR);

        if ($nextWeek < $this->number) {
            return $this->factory->getWeek($nextWeek, $this->year->getNumber() + 1);
        }

        return $this->factory->getWeek($nextWeek, $this->year->getNumber());
    }

    /**
     * Returns the week before this week.
     */
    public function getPreviousWeek(): self
    {
        $calendar = $this->prepareCalendar();
        $calendar->add(IntlCalendar::FIELD_WEEK_OF_YEAR, -1);

        $nextWeek = $calendar->get(IntlCalendar::FIELD_WEEK_OF_YEAR);

        if ($nextWeek > $this->number) {
            return $this->factory->getWeek($nextWeek, $this->year->getNumber() - 1);
        }

        return $this->factory->getWeek($nextWeek, $this->year->getNumber());
    }

    /**
     * Returns the first day of the week.
     */
    public function getFirstDay(): Day
    {
        $calendar = $this->prepareCalendar();

        return $this->factory->getDay($calendar->get(IntlCalendar::FIELD_DATE), $calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));
    }

    /**
     * Returns the last day of the week.
     */
    public function getLastDay(): Day
    {
        $calendar = $this->prepareCalendar();

        while (! $this->isEndOfWeek($calendar)) {
            $calendar->add(IntlCalendar::FIELD_DATE, 1);
        }

        return $this->factory->getDay($calendar->get(IntlCalendar::FIELD_DATE), $calendar->get(IntlCalendar::FIELD_MONTH), $calendar->get(IntlCalendar::FIELD_YEAR));
    }

    /**
     * Returns the year of the week.
     */
    public function getYear(): Year
    {
        return $this->year;
    }

    protected function prepareCalendar(): IntlCalendar
    {
        $calendar = $this->factory->getCalendar();

        $calendar->set($this->year->getNumber(), 0, 1, 0, 0, 0);
        $calendar->set(IntlCalendar::FIELD_WEEK_OF_YEAR, $this->number);
        $calendar->set(IntlCalendar::FIELD_DOW_LOCAL, 1);

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
