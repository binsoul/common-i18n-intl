<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use PHPUnit\Framework\TestCase;

class YearTest extends TestCase
{
    public function test_returns_first_day(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $day = $year->getFirstDay();
        self::assertEquals(1, $day->getNumber());
        self::assertEquals(1, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_last_day(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $day = $year->getLastDay();
        self::assertEquals(31, $day->getNumber());
        self::assertEquals(12, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $days = $year->getDays();
        self::assertCount(365, $days);

        $day = $days[0];
        self::assertEquals(1, $day->getNumber());
        self::assertEquals(1, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());

        $day = $days[364];
        self::assertEquals(31, $day->getNumber());
        self::assertEquals(12, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_first_month(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $month = $year->getFirstMonth();
        self::assertEquals(1, $month->getNumber());
        self::assertEquals(2019, $month->getYear()->getNumber());
    }

    public function test_returns_last_month(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $month = $year->getLastMonth();
        self::assertEquals(12, $month->getNumber());
        self::assertEquals(2019, $month->getYear()->getNumber());
    }

    public function test_returns_months(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $months = $year->getMonths();
        self::assertCount(12, $months);

        $month = $months[0];
        self::assertEquals(1, $month->getNumber());
        self::assertEquals(2019, $month->getYear()->getNumber());

        $month = $months[11];
        self::assertEquals(12, $month->getNumber());
        self::assertEquals(2019, $month->getYear()->getNumber());
    }

    public function test_returns_first_week(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $week = $year->getFirstWeek();
        self::assertEquals(1, $week->getNumber());
        self::assertEquals(2019, $week->getYear()->getNumber());
    }

    public function test_returns_last_week(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $week = $year->getLastWeek();
        self::assertEquals(52, $week->getNumber());
        self::assertEquals(2019, $week->getYear()->getNumber());
    }

    public function test_returns_weeks(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $weeks = $year->getWeeks();
        self::assertCount(52, $weeks);

        $week = $weeks[0];
        self::assertEquals(1, $week->getNumber());
        self::assertEquals(2019, $week->getYear()->getNumber());

        $week = $weeks[51];
        self::assertEquals(52, $week->getNumber());
        self::assertEquals(2019, $week->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $year = $calendar->getYear(2019);

        self::assertEquals('19', $year->getDateShort());
        self::assertEquals('2019', $year->getDateMedium());
        self::assertEquals('2019', $year->getDateLong());
        self::assertEquals('2019', $year->getDateIso());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $year = $calendar->getYear(2019);

        self::assertEquals('١٩', $year->getDateShort());
        self::assertEquals('٢٠١٩', $year->getDateMedium());
        self::assertEquals('٢٠١٩', $year->getDateLong());
        self::assertEquals('2019', $year->getDateIso());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $year = $calendar->getYear(2019);

        self::assertEquals('19', $year->getNameShort());
        self::assertEquals('2019', $year->getNameMedium());
        self::assertEquals('2019', $year->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $year = $calendar->getYear(2019);

        self::assertEquals('١٩', $year->getNameShort());
        self::assertEquals('٢٠١٩', $year->getNameMedium());
        self::assertEquals('٢٠١٩', $year->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getYear(2019);

        self::assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
