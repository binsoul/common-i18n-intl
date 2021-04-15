<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function test_returns_first_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(2, 2020);
        $day = $month->getFirstDay();
        self::assertEquals(1, $day->getNumber());
        self::assertEquals(2, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_last_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(2, 2020);
        $day = $month->getLastDay();
        self::assertEquals(29, $day->getNumber());
        self::assertEquals(2, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(2, 2020);
        $days = $month->getDays();
        self::assertCount(29, $days);

        $day = $days[0];
        self::assertEquals(1, $day->getNumber());
        self::assertEquals(2, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());

        $day = $days[28];
        self::assertEquals(29, $day->getNumber());
        self::assertEquals(2, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_weeks(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(3, 2020);
        $weeks = $month->getWeeks();
        self::assertCount(6, $weeks);

        $week = $weeks[0];
        self::assertEquals(9, $week->getNumber());
        self::assertEquals(2020, $week->getYear()->getNumber());

        $week = $weeks[5];
        self::assertEquals(14, $week->getNumber());
        self::assertEquals(2020, $week->getYear()->getNumber());
    }

    public function test_returns_weeks_end_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(12, 2019);
        $weeks = $month->getWeeks();
        self::assertCount(6, $weeks);

        $week = $weeks[0];
        self::assertEquals(48, $week->getNumber());
        self::assertEquals(2019, $week->getYear()->getNumber());

        $week = $weeks[5];
        self::assertEquals(1, $week->getNumber());
        self::assertEquals(2020, $week->getYear()->getNumber());
    }

    public function test_returns_weeks_start_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(1, 2021);
        $weeks = $month->getWeeks();
        self::assertCount(5, $weeks);

        $week = $weeks[0];
        self::assertEquals(53, $week->getNumber());
        self::assertEquals(2020, $week->getYear()->getNumber());

        $week = $weeks[4];
        self::assertEquals(4, $week->getNumber());
        self::assertEquals(2021, $week->getYear()->getNumber());
    }

    public function test_returns_next_month(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(12, 2019);
        self::assertEquals(1, $month->getNextMonth()->getNumber());
        self::assertEquals(2020, $month->getNextMonth()->getYear()->getNumber());
    }

    public function test_returns_previous_month(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(1, 2020);
        self::assertEquals(12, $month->getPreviousMonth()->getNumber());
        self::assertEquals(2019, $month->getPreviousMonth()->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $month = $calendar->getMonth(1, 2019);

        self::assertEquals('1', $month->getDateShort());
        self::assertEquals('01', $month->getDateMedium());
        self::assertEquals('01 2019', $month->getDateLong());
        self::assertEquals('2019-01', $month->getDateIso());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $month = $calendar->getMonth(1, 2019);

        self::assertEquals('١', $month->getDateShort());
        self::assertEquals('٠١', $month->getDateMedium());
        self::assertEquals('٠١ ٢٠١٩', $month->getDateLong());
        self::assertEquals('2019-01', $month->getDateIso());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $month = $calendar->getMonth(1, 2019);

        self::assertEquals('J', $month->getNameShort());
        self::assertEquals('Jan', $month->getNameMedium());
        self::assertEquals('Januar', $month->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $month = $calendar->getMonth(1, 2019);

        self::assertEquals('ي', $month->getNameShort());
        self::assertEquals('يناير', $month->getNameMedium());
        self::assertEquals('يناير', $month->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getMonth(1, 2019);

        self::assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
