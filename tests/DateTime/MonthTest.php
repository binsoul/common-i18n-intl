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
        $this->assertEquals(1, $day->getNumber());
        $this->assertEquals(2, $day->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_last_day(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(2, 2020);
        $day = $month->getLastDay();
        $this->assertEquals(29, $day->getNumber());
        $this->assertEquals(2, $day->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_days(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(2, 2020);
        $days = $month->getDays();
        $this->assertCount(29, $days);

        $day = $days[0];
        $this->assertEquals(1, $day->getNumber());
        $this->assertEquals(2, $day->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getYear()->getNumber());

        $day = $days[28];
        $this->assertEquals(29, $day->getNumber());
        $this->assertEquals(2, $day->getMonth()->getNumber());
        $this->assertEquals(2020, $day->getYear()->getNumber());
    }

    public function test_returns_weeks(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(3, 2020);
        $weeks = $month->getWeeks();
        $this->assertCount(6, $weeks);

        $week = $weeks[0];
        $this->assertEquals(9, $week->getNumber());
        $this->assertEquals(2020, $week->getYear()->getNumber());

        $week = $weeks[5];
        $this->assertEquals(14, $week->getNumber());
        $this->assertEquals(2020, $week->getYear()->getNumber());
    }

    public function test_returns_weeks_end_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(12, 2019);
        $weeks = $month->getWeeks();
        $this->assertCount(6, $weeks);

        $week = $weeks[0];
        $this->assertEquals(48, $week->getNumber());
        $this->assertEquals(2019, $week->getYear()->getNumber());

        $week = $weeks[5];
        $this->assertEquals(1, $week->getNumber());
        $this->assertEquals(2020, $week->getYear()->getNumber());
    }

    public function test_returns_weeks_start_of_year(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(1, 2021);
        $weeks = $month->getWeeks();
        $this->assertCount(5, $weeks);

        $week = $weeks[0];
        $this->assertEquals(53, $week->getNumber());
        $this->assertEquals(2020, $week->getYear()->getNumber());

        $week = $weeks[4];
        $this->assertEquals(4, $week->getNumber());
        $this->assertEquals(2021, $week->getYear()->getNumber());
    }

    public function test_returns_next_month(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(12, 2019);
        $this->assertEquals(1, $month->getNextMonth()->getNumber());
        $this->assertEquals(2020, $month->getNextMonth()->getYear()->getNumber());
    }

    public function test_returns_previous_month(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));

        $month = $calendar->getMonth(1, 2020);
        $this->assertEquals(12, $month->getPreviousMonth()->getNumber());
        $this->assertEquals(2019, $month->getPreviousMonth()->getYear()->getNumber());
    }

    public function test_formats_dates(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $month = $calendar->getMonth(1, 2019);

        $this->assertEquals('1', $month->getDateShort());
        $this->assertEquals('01', $month->getDateMedium());
        $this->assertEquals('01 2019', $month->getDateLong());
        $this->assertEquals('2019-01', $month->getDateIso());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $month = $calendar->getMonth(1, 2019);

        $this->assertEquals('١', $month->getDateShort());
        $this->assertEquals('٠١', $month->getDateMedium());
        $this->assertEquals('٠١ ٢٠١٩', $month->getDateLong());
        $this->assertEquals('2019-01', $month->getDateIso());
    }

    public function test_formats_names(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $month = $calendar->getMonth(1, 2019);

        $this->assertEquals('J', $month->getNameShort());
        $this->assertEquals('Jan', $month->getNameMedium());
        $this->assertEquals('Januar', $month->getNameLong());

        $calendar = new Calendar(new DefaultLocale('ar-EG'));
        $month = $calendar->getMonth(1, 2019);

        $this->assertEquals('ي', $month->getNameShort());
        $this->assertEquals('يناير', $month->getNameMedium());
        $this->assertEquals('يناير', $month->getNameLong());
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getMonth(1, 2019);

        $this->assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
