<?php

namespace BinSoul\Test\Common\I18n\DateTime;

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

        $this->assertTrue($day->isWeekday());
        $this->assertFalse($day->isWeekend());

        $day = $calendar->getDay(2, 2, 2019);

        $this->assertFalse($day->isWeekday());
        $this->assertTrue($day->isWeekend());
    }

    public function test_compares(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day1 = $calendar->getDay(1, 2, 2019);
        $day2 = $calendar->getDay(2, 2, 2019);

        $this->assertTrue($day1->isSameday($day1));
        $this->assertTrue($day2->isSameday($day2));

        $this->assertTrue($day1->isSameYear($day2->getYear()));
        $this->assertTrue($day1->isSameMonth($day2->getMonth()));
        $this->assertFalse($day1->isSameday($day2));
    }

    public function test_returns_week_end_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(31, 12, 2019);

        $this->assertEquals(1, $day->getWeek()->getNumber());
        $this->assertEquals(2020, $day->getWeek()->getYear()->getNumber());
    }

    public function test_returns_week_start_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(1, 1, 2021);

        $this->assertEquals(53, $day->getWeek()->getNumber());
        $this->assertEquals(2020, $day->getWeek()->getYear()->getNumber());
    }

    public function test_returns_next_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(31, 12, 2019);
        $this->assertEquals(1, $day->getNextDay()->getNumber());
        $this->assertEquals(1, $day->getNextDay()->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getNextDay()->getYear()->getNumber());
    }

    public function test_returns_previous_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $day = $calendar->getDay(1, 1, 2020);
        $this->assertEquals(31, $day->getPreviousDay()->getNumber());
        $this->assertEquals(12, $day->getPreviousDay()->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getPreviousDay()->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getDay(1, 2, 2019);

        $this->assertEquals('1', $day->getDateShort());
        $this->assertEquals('01', $day->getDateMedium());
        $this->assertEquals('01.02.2019', $day->getDateLong());
        $this->assertEquals('2019-02-01', $day->getDateIso());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $day = $calendar->getDay(1, 2, 2019);

        $this->assertEquals('١', $day->getDateShort());
        $this->assertEquals('٠١', $day->getDateMedium());
        $this->assertEquals('٠١‏/٠٢‏/٢٠١٩', $day->getDateLong());
        $this->assertEquals('2019-02-01', $day->getDateIso());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getDay(1, 2, 2019);

        $this->assertEquals('F', $day->getNameShort());
        $this->assertEquals('Fr', $day->getNameMedium());
        $this->assertEquals('Freitag', $day->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $day = $calendar->getDay(1, 2, 2019);

        $this->assertEquals('ج', $day->getNameShort());
        $this->assertEquals('الجمعة', $day->getNameMedium());
        $this->assertEquals('الجمعة', $day->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getDay(1, 2, 2019);

        $this->assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
