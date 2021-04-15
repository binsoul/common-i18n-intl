<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    public function test_returns_years(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        self::assertEquals(2019, $year->getNumber());

        $year = $calendar->getYear(-2019);
        self::assertEquals(-2019, $year->getNumber());

        $year = $calendar->getYear(1002019);
        self::assertEquals(1002019, $year->getNumber());
    }

    public function test_returns_months(): void
    {
        $calendar = new Calendar();

        for ($i = 1; $i <= 12; $i++) {
            $month = $calendar->getMonth($i, 2019);
            self::assertEquals($i, $month->getNumber());
            self::assertEquals(2019, $month->getYear()->getNumber());
        }
    }

    public function test_handles_invalid_months(): void
    {
        $calendar = new Calendar();

        $month = $calendar->getMonth(-1, 2019);
        self::assertEquals(11, $month->getNumber());
        self::assertEquals(2018, $month->getYear()->getNumber());

        $month = $calendar->getMonth(14, 2019);
        self::assertEquals(2, $month->getNumber());
        self::assertEquals(2020, $month->getYear()->getNumber());
    }

    public function test_returns_weeks(): void
    {
        $calendar = new Calendar();

        for ($i = 1; $i <= 52; $i++) {
            $week = $calendar->getWeek($i, 2019);
            self::assertEquals($i, $week->getNumber());
            self::assertEquals(2019, $week->getYear()->getNumber());
        }
    }

    public function test_handles_invalid_weeks(): void
    {
        $calendar = new Calendar();

        $week = $calendar->getWeek(-1, 2019);
        self::assertEquals(51, $week->getNumber());
        self::assertEquals(2018, $week->getYear()->getNumber());

        $week = $calendar->getWeek(54, 2019);
        self::assertEquals(2, $week->getNumber());
        self::assertEquals(2020, $week->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar();

        for ($i = 1; $i <= 31; $i++) {
            $day = $calendar->getDay($i, 1, 2019);
            self::assertEquals($i, $day->getNumber());
            self::assertEquals(1, $day->getMonth()->getNumber());
            self::assertEquals(2019, $day->getYear()->getNumber());
        }
    }

    public function test_handles_invalid_days(): void
    {
        $calendar = new Calendar();

        $day = $calendar->getDay(-1, 1, 2019);
        self::assertEquals(30, $day->getNumber());
        self::assertEquals(12, $day->getMonth()->getNumber());
        self::assertEquals(2018, $day->getYear()->getNumber());

        $day = $calendar->getDay(34, 12, 2019);
        self::assertEquals(3, $day->getNumber());
        self::assertEquals(1, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_creates_days_from_datetime(): void
    {
        $calendar = new Calendar();

        $date = new DateTime('2019-09-08T12:30:00');
        $day = $calendar->fromDate($date);
        self::assertEquals(8, $day->getNumber());
        self::assertEquals(9, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_points_relative_to_current_day(): void
    {
        $calendar = new Calendar();

        $date = new DateTime();
        $day = $calendar->today();
        self::assertEquals($date->format('d'), $day->getNumber());
        self::assertEquals($date->format('m'), $day->getMonth()->getNumber());
        self::assertEquals($date->format('Y'), $day->getYear()->getNumber());

        $date = (new DateTime())->sub(new DateInterval('P1D'));
        $day = $calendar->yesterday();
        self::assertEquals($date->format('d'), $day->getNumber());
        self::assertEquals($date->format('m'), $day->getMonth()->getNumber());
        self::assertEquals($date->format('Y'), $day->getYear()->getNumber());

        $date = (new DateTime())->add(new DateInterval('P1D'));
        $day = $calendar->tomorrow();
        self::assertEquals($date->format('d'), $day->getNumber());
        self::assertEquals($date->format('m'), $day->getMonth()->getNumber());
        self::assertEquals($date->format('Y'), $day->getYear()->getNumber());
    }

    public function test_returns_points_relative_to_current_week(): void
    {
        $calendar = new Calendar();

        $date = new DateTime();
        $week = $calendar->thisWeek();
        self::assertEquals($date->format('W'), $week->getNumber());

        $date = (new DateTime())->sub(new DateInterval('P7D'));
        $week = $calendar->lastWeek();
        self::assertEquals($date->format('W'), $week->getNumber());

        $date = (new DateTime())->add(new DateInterval('P7D'));
        $week = $calendar->nextWeek();
        self::assertEquals($date->format('W'), $week->getNumber());
    }

    public function test_returns_same_object(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $year->getProperties()->set('key', 'value');

        $year = $calendar->getYear(2019);
        self::assertEquals('value', $year->getProperties()->get('key'));

        $month = $calendar->getMonth(1, 2019);
        $month->getProperties()->set('key', 'value');

        $month = $calendar->getMonth(1, 2019);
        self::assertEquals('value', $month->getProperties()->get('key'));

        $week = $calendar->getWeek(1, 2019);
        $week->getProperties()->set('key', 'value');

        $week = $calendar->getWeek(1, 2019);
        self::assertEquals('value', $week->getProperties()->get('key'));

        $day = $calendar->getDay(1, 1, 2019);
        $day->getProperties()->set('key', 'value');

        $day = $calendar->getDay(1, 1, 2019);
        self::assertEquals('value', $day->getProperties()->get('key'));
    }

    public function test_returns_day_of_week_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('en-US'));
        self::assertEquals(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], $calendar->getDayOfWeekNames());

        $calendar = new Calendar(new DefaultLocale('de-DE'));
        self::assertEquals(['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'], $calendar->getDayOfWeekNames());
    }
}
