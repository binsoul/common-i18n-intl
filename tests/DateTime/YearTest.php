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
        $this->assertEquals(1, $day->getNumber());
        $this->assertEquals(1, $day->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_last_day(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $day = $year->getLastDay();
        $this->assertEquals(31, $day->getNumber());
        $this->assertEquals(12, $day->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $days = $year->getDays();
        $this->assertCount(365, $days);

        $day = $days[0];
        $this->assertEquals(1, $day->getNumber());
        $this->assertEquals(1, $day->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getYear()->getNumber());

        $day = $days[364];
        $this->assertEquals(31, $day->getNumber());
        $this->assertEquals(12, $day->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getYear()->getNumber());
    }

    public function test_returns_first_month(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $month = $year->getFirstMonth();
        $this->assertEquals(1, $month->getNumber());
        $this->assertEquals(2019, $month->getYear()->getNumber());
    }

    public function test_returns_last_month(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $month = $year->getLastMonth();
        $this->assertEquals(12, $month->getNumber());
        $this->assertEquals(2019, $month->getYear()->getNumber());
    }

    public function test_returns_months(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $months = $year->getMonths();
        $this->assertCount(12, $months);

        $month = $months[0];
        $this->assertEquals(1, $month->getNumber());
        $this->assertEquals(2019, $month->getYear()->getNumber());

        $month = $months[11];
        $this->assertEquals(12, $month->getNumber());
        $this->assertEquals(2019, $month->getYear()->getNumber());
    }

    public function test_returns_first_week(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $week = $year->getFirstWeek();
        $this->assertEquals(1, $week->getNumber());
        $this->assertEquals(2019, $week->getYear()->getNumber());
    }

    public function test_returns_last_week(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $week = $year->getLastWeek();
        $this->assertEquals(52, $week->getNumber());
        $this->assertEquals(2019, $week->getYear()->getNumber());
    }

    public function test_returns_weeks(): void
    {
        $calendar = new Calendar();

        $year = $calendar->getYear(2019);
        $weeks = $year->getWeeks();
        $this->assertCount(52, $weeks);

        $week = $weeks[0];
        $this->assertEquals(1, $week->getNumber());
        $this->assertEquals(2019, $week->getYear()->getNumber());

        $week = $weeks[51];
        $this->assertEquals(52, $week->getNumber());
        $this->assertEquals(2019, $week->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $year = $calendar->getYear(2019);

        $this->assertEquals('19', $year->getDateShort());
        $this->assertEquals('2019', $year->getDateMedium());
        $this->assertEquals('2019', $year->getDateLong());
        $this->assertEquals('2019', $year->getDateIso());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $year = $calendar->getYear(2019);

        $this->assertEquals('١٩', $year->getDateShort());
        $this->assertEquals('٢٠١٩', $year->getDateMedium());
        $this->assertEquals('٢٠١٩', $year->getDateLong());
        $this->assertEquals('2019', $year->getDateIso());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $year = $calendar->getYear(2019);

        $this->assertEquals('19', $year->getNameShort());
        $this->assertEquals('2019', $year->getNameMedium());
        $this->assertEquals('2019', $year->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $year = $calendar->getYear(2019);

        $this->assertEquals('١٩', $year->getNameShort());
        $this->assertEquals('٢٠١٩', $year->getNameMedium());
        $this->assertEquals('٢٠١٩', $year->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getYear(2019);

        $this->assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
