<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use PHPUnit\Framework\TestCase;

class WeekTest extends TestCase
{
    public function test_returns_first_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(1, 2019);
        $day = $week->getFirstDay();
        self::assertEquals(31, $day->getNumber());
        self::assertEquals(12, $day->getMonth()->getNumber());
        self::assertEquals(2018, $day->getYear()->getNumber());
    }

    public function test_returns_last_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(52, 2019);
        $day = $week->getLastDay();
        self::assertEquals(29, $day->getNumber());
        self::assertEquals(12, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());

        $week = $calendar->getWeek(1, 2020);
        $day = $week->getLastDay();
        self::assertEquals(5, $day->getNumber());
        self::assertEquals(1, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(1, 2020);
        $days = $week->getDays();
        self::assertCount(7, $days);

        $day = $days[0];
        self::assertEquals(30, $day->getNumber());
        self::assertEquals(12, $day->getMonth()->getNumber());
        self::assertEquals(2019, $day->getYear()->getNumber());

        $day = $days[6];
        self::assertEquals(5, $day->getNumber());
        self::assertEquals(1, $day->getMonth()->getNumber());
        self::assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_next_week(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(52, 2019);
        self::assertEquals(1, $week->getNextWeek()->getNumber());
        self::assertEquals(2020, $week->getNextWeek()->getYear()->getNumber());
    }

    public function test_returns_previous_week(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(1, 2020);
        self::assertEquals(52, $week->getPreviousWeek()->getNumber());
        self::assertEquals(2019, $week->getPreviousWeek()->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $week = $calendar->getWeek(1, 2019);

        self::assertEquals('1', $week->getDateShort());
        self::assertEquals('01', $week->getDateMedium());
        self::assertEquals('01 2019', $week->getDateLong());

        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $week = $calendar->getWeek(42, 2019);

        self::assertEquals('42', $week->getDateShort());
        self::assertEquals('42', $week->getDateMedium());
        self::assertEquals('42 2019', $week->getDateLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $week = $calendar->getWeek(1, 2019);

        self::assertEquals('١', $week->getDateShort());
        self::assertEquals('٠١', $week->getDateMedium());
        self::assertEquals('٠١ ٢٠١٩', $week->getDateLong());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $week = $calendar->getWeek(1, 2019);

        self::assertEquals('1', $week->getNameShort());
        self::assertEquals('01', $week->getNameMedium());
        self::assertEquals('01', $week->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $week = $calendar->getWeek(1, 2019);

        self::assertEquals('١', $week->getNameShort());
        self::assertEquals('٠١', $week->getNameMedium());
        self::assertEquals('٠١', $week->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getWeek(1, 2019);

        self::assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
