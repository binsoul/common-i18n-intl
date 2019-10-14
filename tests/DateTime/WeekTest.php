<?php

namespace BinSoul\Test\Common\I18n\DateTime;

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
        $this->assertEquals(31, $day->getNumber());
        $this->assertEquals(12, $day->getMonth()->getNumber());
        $this->assertEquals(2018, $day->getYear()->getNumber());
    }

    public function test_returns_last_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(52, 2019);
        $day = $week->getLastDay();
        $this->assertEquals(29, $day->getNumber());
        $this->assertEquals(12, $day->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getYear()->getNumber());

        $week = $calendar->getWeek(1, 2020);
        $day = $week->getLastDay();
        $this->assertEquals(5, $day->getNumber());
        $this->assertEquals(1, $day->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(1, 2020);
        $days = $week->getDays();
        $this->assertCount(7, $days);

        $day = $days[0];
        $this->assertEquals(30, $day->getNumber());
        $this->assertEquals(12, $day->getMonth()->getNumber());
        $this->assertEquals(2019, $day->getYear()->getNumber());

        $day = $days[6];
        $this->assertEquals(5, $day->getNumber());
        $this->assertEquals(1, $day->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_next_week(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(52, 2019);
        $this->assertEquals(1, $week->getNextWeek()->getNumber());
        $this->assertEquals(2020, $week->getNextWeek()->getYear()->getNumber());
    }

    public function test_returns_previous_week(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $week = $calendar->getWeek(1, 2020);
        $this->assertEquals(52, $week->getPreviousWeek()->getNumber());
        $this->assertEquals(2019, $week->getPreviousWeek()->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $week = $calendar->getWeek(1, 2019);

        $this->assertEquals('1', $week->getDateShort());
        $this->assertEquals('01', $week->getDateMedium());
        $this->assertEquals('01 2019', $week->getDateLong());

        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $week = $calendar->getWeek(42, 2019);

        $this->assertEquals('42', $week->getDateShort());
        $this->assertEquals('42', $week->getDateMedium());
        $this->assertEquals('42 2019', $week->getDateLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $week = $calendar->getWeek(1, 2019);

        $this->assertEquals('١', $week->getDateShort());
        $this->assertEquals('٠١', $week->getDateMedium());
        $this->assertEquals('٠١ ٢٠١٩', $week->getDateLong());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $week = $calendar->getWeek(1, 2019);

        $this->assertEquals('1', $week->getNameShort());
        $this->assertEquals('01', $week->getNameMedium());
        $this->assertEquals('01', $week->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $week = $calendar->getWeek(1, 2019);

        $this->assertEquals('١', $week->getNameShort());
        $this->assertEquals('٠١', $week->getNameMedium());
        $this->assertEquals('٠١', $week->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getWeek(1, 2019);

        $this->assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
