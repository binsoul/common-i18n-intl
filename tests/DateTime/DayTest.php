<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('weekDataProvider')]
    public function test_returns_week(string $locale, int $day, int $month, int $year, int $expectedWeek, int $expectedYear): void
    {
        $calendar = new Calendar(new DefaultLocale($locale));
        $dayObj = $calendar->getDay($day, $month, $year);

        self::assertEquals($expectedWeek, $dayObj->getWeek()->getNumber(), "Failed for {$locale} {$year}-{$month}-{$day}");
        self::assertEquals($expectedYear, $dayObj->getWeek()->getYear()->getNumber(), "Failed Year for {$locale} {$year}-{$month}-{$day}");
    }

    /**
     * @return array<string, array{string, int, int, int, int, int}>
     */
    public static function weekDataProvider(): array
    {
        return [
            // Germany (ISO-8601)
            'DE 2019-12-31' => ['de-DE', 31, 12, 2019, 1, 2020],
            'DE 2021-01-01' => ['de-DE', 1, 1, 2021, 53, 2020],
            'DE 2022-01-01' => ['de-DE', 1, 1, 2022, 52, 2021],
            'DE 2022-01-03' => ['de-DE', 3, 1, 2022, 1, 2022],
            'DE 2025-12-28' => ['de-DE', 28, 12, 2025, 52, 2025],
            'DE 2025-12-29' => ['de-DE', 29, 12, 2025, 1, 2026],
            'DE 2026-12-28' => ['de-DE', 28, 12, 2026, 53, 2026],
            'DE 2027-01-03' => ['de-DE', 3, 1, 2027, 53, 2026],
            'DE 2027-01-04' => ['de-DE', 4, 1, 2027, 1, 2027],

            // Egypt (Arabic, often starts on Saturday)
            'EG 2021-12-24' => ['ar-EG', 24, 12, 2021, 52, 2021],
            'EG 2021-12-25' => ['ar-EG', 25, 12, 2021, 53, 2021],
            'EG 2021-12-31' => ['ar-EG', 31, 12, 2021, 53, 2021],
            'EG 2022-01-01' => ['ar-EG', 1, 1, 2022, 1, 2022],
            'EG 2026-12-25' => ['ar-EG', 25, 12, 2026, 52, 2026],
            'EG 2026-12-26' => ['ar-EG', 26, 12, 2026, 1, 2027],
            'EG 2027-12-24' => ['ar-EG', 24, 12, 2027, 52, 2027],
            'EG 2027-12-25' => ['ar-EG', 25, 12, 2027, 53, 2027],
            'EG 2027-12-31' => ['ar-EG', 31, 12, 2027, 53, 2027],
            'EG 2028-01-01' => ['ar-EG', 1, 1, 2028, 1, 2028],

            // US (Starts on Sunday)
            'US 2025-01-01' => ['en-US', 1, 1, 2025, 1, 2025],
            'US 2026-12-26' => ['en-US', 26, 12, 2026, 52, 2026],
            'US 2026-12-27' => ['en-US', 27, 12, 2026, 1, 2027],
            'US 2027-01-01' => ['en-US', 1, 1, 2027, 1, 2027],

            // Iran (Persian, starts on Saturday)
            'IR 2026-12-26' => ['fa-IR', 26, 12, 2026, 52, 2026],
            'IR 2027-01-01' => ['fa-IR', 1, 1, 2027, 1, 2027],
            'IR 2027-03-21' => ['fa-IR', 21, 3, 2027, 13, 2027],

            // Thailand
            'TH 2025-01-01' => ['th-TH', 1, 1, 2025, 1, 2025],
            'TH 2027-01-01' => ['th-TH', 1, 1, 2027, 1, 2027],
            'TH 2028-12-30' => ['th-TH', 30, 12, 2028, 53, 2028],
            'TH 2028-12-31' => ['th-TH', 31, 12, 2028, 53, 2028],
            'TH 2029-01-01' => ['th-TH', 1, 1, 2029, 1, 2029],
        ];
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
