<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\DateTime\Calendar;
use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('weekNavigationDataProvider')]
    public function test_navigation(string $locale, int $startWeek, int $startYear, int $nextWeek, int $nextYear, int $prevWeek, int $prevYear): void
    {
        $calendar = new Calendar(new DefaultLocale($locale));
        $week = $calendar->getWeek($startWeek, $startYear);

        self::assertEquals($nextWeek, $week->getNextWeek()->getNumber(), "Next week failed for {$locale} {$startYear}-W{$startWeek}");
        self::assertEquals($nextYear, $week->getNextWeek()->getYear()->getNumber(), "Next year failed for {$locale} {$startYear}-W{$startWeek}");

        self::assertEquals($prevWeek, $week->getPreviousWeek()->getNumber(), "Previous week failed for {$locale} {$startYear}-W{$startWeek}");
        self::assertEquals($prevYear, $week->getPreviousWeek()->getYear()->getNumber(), "Previous year failed for {$locale} {$startYear}-W{$startWeek}");
    }

    /**
     * @return array<string, array{string, int, int, int, int, int, int}>
     */
    public static function weekNavigationDataProvider(): array
    {
        return [
            'DE 2019-W52' => ['de-DE', 52, 2019, 1, 2020, 51, 2019],
            'DE 2020-W1' => ['de-DE', 1, 2020, 2, 2020, 52, 2019],
            'DE 2025-W52' => ['de-DE', 52, 2025, 1, 2026, 51, 2025],
            'DE 2026-W1' => ['de-DE', 1, 2026, 2, 2026, 52, 2025],
            'DE 2026-W52' => ['de-DE', 52, 2026, 53, 2026, 51, 2026],
            'DE 2026-W53' => ['de-DE', 53, 2026, 1, 2027, 52, 2026],
            'DE 2027-W1' => ['de-DE', 1, 2027, 2, 2027, 53, 2026],

            'EG 2026-W52' => ['ar-EG', 52, 2026, 1, 2027, 51, 2026],
            'EG 2027-W1' => ['ar-EG', 1, 2027, 2, 2027, 52, 2026],

            'US 2026-W52' => ['en-US', 52, 2026, 1, 2027, 51, 2026],
            'US 2027-W1' => ['en-US', 1, 2027, 2, 2027, 52, 2026],

            'TH 2026-W52' => ['th-TH', 52, 2026, 1, 2027, 51, 2026],
            'TH 2027-W1' => ['th-TH', 1, 2027, 2, 2027, 52, 2026],
            'TH 2028-W52' => ['th-TH', 52, 2028, 53, 2028, 51, 2028],
            'TH 2028-W53' => ['th-TH', 53, 2028, 1, 2029, 52, 2028],
            'TH 2029-W1' => ['th-TH', 1, 2029, 2, 2029, 53, 2028],
        ];
    }

    #[DataProvider('formatDataProvider')]
    public function test_formats_dates(string $locale, int $weekNumber, int $year, string $short, string $medium, string $long): void
    {
        $calendar = new Calendar(new DefaultLocale($locale));
        $week = $calendar->getWeek($weekNumber, $year);

        self::assertEquals($short, $week->getDateShort(), "Short failed for {$locale}");
        self::assertEquals($medium, $week->getDateMedium(), "Medium failed for {$locale}");
        self::assertEquals($long, $week->getDateLong(), "Long failed for {$locale}");
    }

    /**
     * @return array<string, array{string, int, int, string, string, string}>
     */
    public static function formatDataProvider(): array
    {
        return [
            'DE W1 2019' => ['de-DE', 1, 2019, '1', '01', '01 2019'],
            'DE W42 2019' => ['de-DE', 42, 2019, '42', '42', '42 2019'],
            'EG W1 2019' => ['ar-EG', 1, 2019, '١', '٠١', '٠١ ٢٠١٩'],
            'US W1 2019' => ['en-US', 1, 2019, '1', '01', '01 2019'],
            'US W52 2019' => ['en-US', 52, 2019, '52', '52', '52 2019'],
            'FR W1 2019' => ['fr-FR', 1, 2019, '1', '01', '01 2019'],
            'IR W1 2019' => ['fa-IR', 1, 2019, '۱', '۰۱', '۰۱ ۲۰۱۹'],
            'IR W52 2019' => ['fa-IR', 52, 2019, '۵۲', '۵۲', '۵۲ ۲۰۱۹'],
            'TH W1 2019' => ['th-TH', 1, 2019, '1', '01', '01 2019'],
            'TH W52 2019' => ['th-TH', 52, 2019, '52', '52', '52 2019'],
            'TH W53 2028' => ['th-TH', 53, 2028, '53', '53', '53 2028'],
        ];
    }

    #[DataProvider('nameFormatDataProvider')]
    public function test_formats_names(string $locale, int $weekNumber, int $year, string $short, string $medium, string $long): void
    {
        $calendar = new Calendar(new DefaultLocale($locale));
        $week = $calendar->getWeek($weekNumber, $year);

        self::assertEquals($short, $week->getNameShort());
        self::assertEquals($medium, $week->getNameMedium());
        self::assertEquals($long, $week->getNameLong());
    }

    /**
     * @return array<string, array{string, int, int, string, string, string}>
     */
    public static function nameFormatDataProvider(): array
    {
        return [
            'DE W1 2019' => ['de-DE', 1, 2019, '1', '01', '01'],
            'DE W52 2019' => ['de-DE', 52, 2019, '52', '52', '52'],
            'EG W1 2019' => ['ar-EG', 1, 2019, '١', '٠١', '٠١'],
            'EG W52 2019' => ['ar-EG', 52, 2019, '٥٢', '٥٢', '٥٢'],
            'US W1 2019' => ['en-US', 1, 2019, '1', '01', '01'],
            'US W52 2019' => ['en-US', 52, 2019, '52', '52', '52'],
            'FR W1 2019' => ['fr-FR', 1, 2019, '1', '01', '01'],
            'IR W1 2019' => ['fa-IR', 1, 2019, '۱', '۰۱', '۰۱'],
            'TH W1 2019' => ['th-TH', 1, 2019, '1', '01', '01'],
            'TH W52 2019' => ['th-TH', 52, 2019, '52', '52', '52'],
            'TH W53 2028' => ['th-TH', 53, 2028, '53', '53', '53'],
        ];
    }

    public function test_has_properties(): void
    {
        $calendar = new Calendar(new DefaultLocale('de-DE'));
        $day = $calendar->getWeek(1, 2019);

        self::assertInstanceOf(PropertyBag::class, $day->getProperties());
    }
}
