<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use PHPUnit\Framework\TestCase;

class DayTest extends TestCase
{
    public function test_is_weekend(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(1, 2, 2019);

        self::assertTrue($day->isWeekday());
        self::assertFalse($day->isWeekend());

        $day = $calendar->getDay(2, 2, 2019);

        self::assertFalse($day->isWeekday());
        self::assertTrue($day->isWeekend());
    }

    public function test_compares(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day1 = $calendar->getDay(1, 2, 2019);
        $day2 = $calendar->getDay(2, 2, 2019);

        self::assertTrue($day1->isSameday($day1));
        self::assertTrue($day2->isSameday($day2));

        self::assertTrue($day1->isSameYear($day2->getYear()));
        self::assertTrue($day1->isSameMonth($day2->getMonth()));
        self::assertFalse($day1->isSameday($day2));
    }

    public function test_returns_week_end_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(31, 12, 2019);

        self::assertEquals(1, $day->getWeek()->getNumber());
        self::assertEquals(2020, $day->getWeek()->getYear()->getNumber());
    }

    public function test_returns_week_start_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(1, 1, 2021);

        self::assertEquals(53, $day->getWeek()->getNumber());
        self::assertEquals(2020, $day->getWeek()->getYear()->getNumber());

        $day = $calendar->getDay(1, 1, 2022);
        self::assertEquals(52, $day->getWeek()->getNumber());
        self::assertEquals(2021, $day->getWeek()->getYear()->getNumber());

        $day = $calendar->getDay(3, 1, 2022);
        self::assertEquals(1, $day->getWeek()->getNumber());
        self::assertEquals(2022, $day->getWeek()->getYear()->getNumber());
    }

    public function test_returns_next_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(31, 12, 2019);
        self::assertEquals(1, $day->getNextDay()->getNumber());
        self::assertEquals(1, $day->getNextDay()->getMonth()->getNumber());
        self::assertEquals(2020, $day->getNextDay()->getYear()->getNumber());
    }

    public function test_returns_previous_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(1, 1, 2020);
        self::assertEquals(31, $day->getPreviousDay()->getNumber());
        self::assertEquals(12, $day->getPreviousDay()->getMonth()->getNumber());
        self::assertEquals(2019, $day->getPreviousDay()->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getDay(1, 2, 2019);

        self::assertEquals('1', $day->getDateShort());
        self::assertEquals('01', $day->getDateMedium());
        self::assertEquals('01.02.2019', $day->getDateLong());
        self::assertEquals('2019-02-01', $day->getDateIso());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $day = $calendar->getDay(1, 2, 2019);

        self::assertEquals('١', $day->getDateShort());
        self::assertEquals('٠١', $day->getDateMedium());
        self::assertEquals('٠١‏/٠٢‏/٢٠١٩', $day->getDateLong());
        self::assertEquals('2019-02-01', $day->getDateIso());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getDay(1, 2, 2019);

        self::assertEquals('F', $day->getNameShort());
        self::assertEquals('Fr', $day->getNameMedium());
        self::assertEquals('Freitag', $day->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $day = $calendar->getDay(1, 2, 2019);

        self::assertEquals('ج', $day->getNameShort());
        self::assertEquals('الجمعة', $day->getNameMedium());
        self::assertEquals('الجمعة', $day->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getDay(1, 2, 2019);

        self::assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
