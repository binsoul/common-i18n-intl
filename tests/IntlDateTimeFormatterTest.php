<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use IntlCalendar;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IntlDateTimeFormatterTest extends TestCase
{
    public function test_formats_pattern(): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('2019-02-03', $formatter->formatPattern(new DateTime('2019-02-03'), 'yyyy-MM-dd'));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function dates(): array
    {
        return [
            ['en-US', '02/01/2019'],
            ['de-DE', '01.02.2019'],
            ['es-ES', '01/02/2019'],
            ['fa-IR', '۲۰۱۹/۰۲/۰۱'],
            ['ar-EG', '٠١‏/٠٢‏/٢٠١٩'],
        ];
    }

    #[DataProvider('dates')]
    public function test_formats_date(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        self::assertEquals($expected, $formatter->formatDate(new DateTime('2019-02-01')));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function times(): array
    {
        return [
            ['en-US', '02:07 PM'],
            ['de-DE', '14:07'],
            ['fa-IR', '۱۴:۰۷'],
            ['ar-EG', '٠٢:٠٧ م'],
        ];
    }

    #[DataProvider('times')]
    public function test_formats_time(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        self::assertEquals($expected, $formatter->formatTime(new DateTime('2019-02-01T14:07:09')));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function timesWithSeconds(): array
    {
        return [
            ['en-US', '02:07:09 PM'],
            ['de-DE', '14:07:09'],
            ['fa-IR', '۱۴:۰۷:۰۹'],
            ['ar-EG', '٠٢:٠٧:٠٩ م'],
        ];
    }

    #[DataProvider('timesWithSeconds')]
    public function test_formats_time_with_seconds(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        self::assertEquals($expected, $formatter->formatTimeWithSeconds(new DateTime('2019-02-01T14:07:09')));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function dateTimes(): array
    {
        return [
            ['en-US', '02/01/2019, 02:07 PM'],
            ['de-DE', '01.02.2019, 14:07'],
            ['fa-IR', '۲۰۱۹/۰۲/۰۱, ۱۴:۰۷'],
            ['ar-EG', '٠١‏/٠٢‏/٢٠١٩، ٠٢:٠٧ م'],
        ];
    }

    #[DataProvider('dateTimes')]
    public function test_formats_date_times(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        self::assertEquals($expected, $formatter->formatDateTime(new DateTime('2019-02-01T14:07:09')));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function dateTimesWithSeconds(): array
    {
        return [
            ['en-US', '02/01/2019, 02:07:09 PM'],
            ['de-DE', '01.02.2019, 14:07:09'],
            ['fa-IR', '۲۰۱۹/۰۲/۰۱, ۱۴:۰۷:۰۹'],
            ['ar-EG', '٠١‏/٠٢‏/٢٠١٩، ٠٢:٠٧:٠٩ م'],
        ];
    }

    #[DataProvider('dateTimesWithSeconds')]
    public function test_formats_date_times_with_seconds(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        self::assertEquals($expected, $formatter->formatDateTimeWithSeconds(new DateTime('2019-02-01T14:07:09')));
    }

    public function test_with_locale(): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('de-DE'));

        self::assertSame($newFormatter, $formatter);

        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('en-US'));
        self::assertEquals('02/01/2019', $newFormatter->formatDate(new DateTime('2019-02-01')));
    }

    public function test_formats_patterns(): void
    {
        $date = new DateTime('2019-05-01 01:02:03.456', new DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('01.05.2019, Mittwoch', $formatter->formatPattern($date, 'dd.MM.yyyy, EEEE'));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function patterns(): array
    {
        return [
            'ATOM' => [DateTimeInterface::ATOM, IntlDateTimeFormatter::ATOM],
            'COOKIE' => [DateTimeInterface::COOKIE, IntlDateTimeFormatter::COOKIE],
            'RFC822' => [DateTimeInterface::RFC822, IntlDateTimeFormatter::RFC822],
            'RFC850' => [DateTimeInterface::RFC850, IntlDateTimeFormatter::RFC850],
            'RFC1036' => [DateTimeInterface::RFC1036, IntlDateTimeFormatter::RFC1036],
            'RFC1123' => [DateTimeInterface::RFC1123, IntlDateTimeFormatter::RFC1123],
            'RFC2822' => [DateTimeInterface::RFC2822, IntlDateTimeFormatter::RFC2822],
            'RFC3339' => [DateTimeInterface::RFC3339, IntlDateTimeFormatter::RFC3339],
            'RFC3339_EXTENDED' => [DateTimeInterface::RFC3339_EXTENDED, IntlDateTimeFormatter::RFC3339_EXTENDED],
            'RFC7231' => [DateTimeInterface::RFC7231, IntlDateTimeFormatter::RFC7231],
            'RSS' => [DateTimeInterface::RSS, IntlDateTimeFormatter::RSS],
            'W3C' => [DateTimeInterface::W3C, IntlDateTimeFormatter::W3C],
        ];
    }

    #[DataProvider('patterns')]
    public function test_convert_to_icu(string $phpPattern, string $icuPattern): void
    {
        self::assertEquals($icuPattern, IntlDateTimeFormatter::convertToIcu($phpPattern));
    }

    #[DataProvider('patterns')]
    public function test_predefined_patterns_are_equal(string $phpPattern, string $icuPattern): void
    {
        $date = new DateTime('2019-05-01 01:02:03.456', new DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('en'));
        self::assertEquals($date->format($phpPattern), $formatter->formatPattern($date, $icuPattern));
    }

    #[DataProvider('patterns')]
    public function test_predefined_patterns_are_english(string $phpPattern, string $icuPattern): void
    {
        $date = new DateTime('2019-05-01 01:02:03.456', new DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals($date->format($phpPattern), $formatter->formatPattern($date, $icuPattern));
    }

    #[DataProvider('patterns')]
    public function test_formats_objects(string $phpPattern, string $icuPattern): void
    {
        $date = new DateTime('2019-05-01 01:02:03.456', new DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals($date->format($phpPattern), $formatter->formatObject($date, $icuPattern));

        $calendar = IntlCalendar::fromDateTime($date);
        $calendar->set(IntlCalendar::FIELD_MILLISECOND, 456);

        self::assertEquals($date->format($phpPattern), $formatter->formatObject($calendar, $icuPattern));
    }

    public function test_formats_objects_with_locale(): void
    {
        $date = new DateTime('2019-05-01 01:02:03.456', new DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('en-US'));
        self::assertEquals('01.05.2019, Mittwoch', $formatter->formatObject($date, 'dd.MM.yyyy, EEEE', 'de-DE'));
    }

    public function test_uses_default_locale(): void
    {
        $date = new DateTime('2019-05-01 01:02:03.456', new DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter();
        self::assertEquals('2019', $formatter->formatObject($date, 'yyyy'));
    }

    public function test_returns_patterns(): void
    {
        $date = new DateTime('2019-02-01T14:07:09', new DateTimeZone('UTC'));
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));

        self::assertEquals('HH:mm', $formatter->getTimePattern());
        self::assertEquals('HH:mm:ss', $formatter->getTimeWithSecondsPattern());
        self::assertEquals('dd.MM.y', $formatter->getDatePattern());
        self::assertEquals('dd.MM.y, HH:mm', $formatter->getDateTimePattern());
        self::assertEquals('dd.MM.y, HH:mm:ss', $formatter->getDateTimeWithSecondsPattern());

        self::assertEquals($formatter->formatTime($date), $formatter->formatPattern($date, $formatter->getTimePattern()));
        self::assertEquals($formatter->formatTimeWithSeconds($date), $formatter->formatPattern($date, $formatter->getTimeWithSecondsPattern()));
        self::assertEquals($formatter->formatDate($date), $formatter->formatPattern($date, $formatter->getDatePattern()));
        self::assertEquals($formatter->formatDateTime($date), $formatter->formatPattern($date, $formatter->getDateTimePattern()));
        self::assertEquals($formatter->formatDateTimeWithSeconds($date), $formatter->formatPattern($date, $formatter->getDateTimeWithSecondsPattern()));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('en-US'));
        self::assertEquals('hh:mm a', $formatter->getTimePattern());
        self::assertEquals('hh:mm:ss a', $formatter->getTimeWithSecondsPattern());
        self::assertEquals('MM/dd/y', $formatter->getDatePattern());
        self::assertEquals('MM/dd/y, hh:mm a', $formatter->getDateTimePattern());
        self::assertEquals('MM/dd/y, hh:mm:ss a', $formatter->getDateTimeWithSecondsPattern());

        self::assertEquals($formatter->formatTime($date), $formatter->formatPattern($date, $formatter->getTimePattern()));
        self::assertEquals($formatter->formatTimeWithSeconds($date), $formatter->formatPattern($date, $formatter->getTimeWithSecondsPattern()));
        self::assertEquals($formatter->formatDate($date), $formatter->formatPattern($date, $formatter->getDatePattern()));
        self::assertEquals($formatter->formatDateTime($date), $formatter->formatPattern($date, $formatter->getDateTimePattern()));
        self::assertEquals($formatter->formatDateTimeWithSeconds($date), $formatter->formatPattern($date, $formatter->getDateTimeWithSecondsPattern()));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('ar-EG'));
        self::assertEquals('hh:mm a', $formatter->getTimePattern());
        self::assertEquals('hh:mm:ss a', $formatter->getTimeWithSecondsPattern());
        self::assertEquals('dd‏/MM‏/y', $formatter->getDatePattern());
        self::assertEquals('dd‏/MM‏/y، hh:mm a', $formatter->getDateTimePattern());
        self::assertEquals('dd‏/MM‏/y، hh:mm:ss a', $formatter->getDateTimeWithSecondsPattern());

        self::assertEquals($formatter->formatTime($date), $formatter->formatPattern($date, $formatter->getTimePattern()));
        self::assertEquals($formatter->formatTimeWithSeconds($date), $formatter->formatPattern($date, $formatter->getTimeWithSecondsPattern()));
        self::assertEquals($formatter->formatDate($date), $formatter->formatPattern($date, $formatter->getDatePattern()));
        self::assertEquals($formatter->formatDateTime($date), $formatter->formatPattern($date, $formatter->getDateTimePattern()));
        self::assertEquals($formatter->formatDateTimeWithSeconds($date), $formatter->formatPattern($date, $formatter->getDateTimeWithSecondsPattern()));
    }

    public function test_format_pattern_with_timezone(): void
    {
        $date = new DateTime('2019-05-01 01:02:03', new DateTimeZone('Europe/Berlin'));
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));

        self::assertEquals('01.05.2019, CEST', $formatter->formatPattern($date, 'dd.MM.yyyy, z'));
    }
}
