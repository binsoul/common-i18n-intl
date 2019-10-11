<?php

namespace BinSoul\Test\Common\I18n;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\IntlDateTimeFormatter;
use DateTime;
use PHPUnit\Framework\TestCase;

class IntlDateTimeFormatterTest extends TestCase
{
    public function test_formats_pattern(): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('2019-02-03', $formatter->formatPattern(new DateTime('2019-02-03'), 'yyyy-MM-dd'));
    }

    public function dates(): array
    {
        return [
            ['en-US', '02/01/2019'],
            ['de-DE', '01.02.2019'],
            ['es-ES', '01/02/2019'],
            ['fa-IR', '۲۰۱۹/۰۲/۰۱'],
            ['ar-EG', '٠١‏/٠٢‏/٢٠١٩'],
        ];
    }

    /**
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider dates
     */
    public function test_formats_date(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        $this->assertEquals($expected, $formatter->formatDate(new DateTime('2019-02-01')));
    }

    public function times(): array
    {
        return [
            ['en-US', '02:07 PM'],
            ['de-DE', '14:07'],
            ['fa-IR', '۱۴:۰۷'],
            ['ar-EG', '٠٢:٠٧ م'],
        ];
    }

    /**
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider times
     */
    public function test_formats_time(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        $this->assertEquals($expected, $formatter->formatTime(new DateTime('2019-02-01T14:07:09')));
    }

    public function timesWithSeconds(): array
    {
        return [
            ['en-US', '02:07:09 PM'],
            ['de-DE', '14:07:09'],
            ['fa-IR', '۱۴:۰۷:۰۹'],
            ['ar-EG', '٠٢:٠٧:٠٩ م'],
        ];
    }

    /**
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider timesWithSeconds
     */
    public function test_formats_time_with_seconds(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        $this->assertEquals($expected, $formatter->formatTimeWithSeconds(new DateTime('2019-02-01T14:07:09')));
    }

    public function dateTimes(): array
    {
        return [
            ['en-US', '02/01/2019, 02:07 PM'],
            ['de-DE', '01.02.2019, 14:07'],
            ['fa-IR', '۲۰۱۹/۰۲/۰۱،‏ ۱۴:۰۷'],
            ['ar-EG', '٠١‏/٠٢‏/٢٠١٩ ٠٢:٠٧ م'],
        ];
    }

    /**
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider dateTimes
     */
    public function test_formats_dateTimes(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        $this->assertEquals($expected, $formatter->formatDateTime(new DateTime('2019-02-01T14:07:09')));
    }

    public function dateTimesWithSeconds(): array
    {
        return [
            ['en-US', '02/01/2019, 02:07:09 PM'],
            ['de-DE', '01.02.2019, 14:07:09'],
            ['fa-IR', '۲۰۱۹/۰۲/۰۱،‏ ۱۴:۰۷:۰۹'],
            ['ar-EG', '٠١‏/٠٢‏/٢٠١٩ ٠٢:٠٧:٠٩ م'],
        ];
    }

    /**
     * @param string $locale
     * @param string $expected
     *
     * @dataProvider dateTimesWithSeconds
     */
    public function test_formats_dateTimes_with_seconds(string $locale, string $expected): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString($locale));
        $this->assertEquals($expected, $formatter->formatDateTimeWithSeconds(new DateTime('2019-02-01T14:07:09')));
    }

    public function test_with_locale(): void
    {
        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('de-DE'));

        $this->assertSame($newFormatter, $formatter);

        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('en-US'));
        $this->assertEquals('02/01/2019', $newFormatter->formatDate(new DateTime('2019-02-01')));
    }

    public function test_formats_patterns(): void
    {
        $date = new \DateTime('2019-05-01 01:02:03.456', new \DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('01.05.2019, Mittwoch', $formatter->formatPattern($date, 'dd.MM.yyyy, EEEE'));
    }

    public function patterns(): array
    {
        return [
            \DateTimeInterface::ATOM => [\DateTimeInterface::ATOM, IntlDateTimeFormatter::ATOM],
            \DateTimeInterface::COOKIE => [\DateTimeInterface::COOKIE, IntlDateTimeFormatter::COOKIE],
            \DateTimeInterface::RFC822 => [\DateTimeInterface::RFC822, IntlDateTimeFormatter::RFC822],
            \DateTimeInterface::RFC850 => [\DateTimeInterface::RFC850, IntlDateTimeFormatter::RFC850],
            \DateTimeInterface::RFC1036 => [\DateTimeInterface::RFC1036, IntlDateTimeFormatter::RFC1036],
            \DateTimeInterface::RFC1123 => [\DateTimeInterface::RFC1123, IntlDateTimeFormatter::RFC1123],
            \DateTimeInterface::RFC2822 => [\DateTimeInterface::RFC2822, IntlDateTimeFormatter::RFC2822],
            \DateTimeInterface::RFC3339 => [\DateTimeInterface::RFC3339, IntlDateTimeFormatter::RFC3339],
            \DateTimeInterface::RFC3339_EXTENDED => [\DateTimeInterface::RFC3339_EXTENDED, IntlDateTimeFormatter::RFC3339_EXTENDED],
            \DateTimeInterface::RFC7231 => [\DateTimeInterface::RFC7231, IntlDateTimeFormatter::RFC7231],
            \DateTimeInterface::RSS => [\DateTimeInterface::RSS, IntlDateTimeFormatter::RSS],
            \DateTimeInterface::W3C => [\DateTimeInterface::W3C, IntlDateTimeFormatter::W3C],
        ];
    }

    /**
     * @dataProvider patterns
     */
    public function test_convert_to_icu(string $phpPattern, string $icuPattern): void
    {
        $this->assertEquals($icuPattern, IntlDateTimeFormatter::convertToIcu($phpPattern));
    }

    /**
     * @dataProvider patterns
     */
    public function test_predefined_patterns_are_equal(string $phpPattern, string $icuPattern): void
    {
        $date = new \DateTime('2019-05-01 01:02:03.456', new \DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('en'));
        $this->assertEquals($date->format($phpPattern), $formatter->formatPattern($date, $icuPattern));
    }

    /**
     * @dataProvider patterns
     */
    public function test_predefined_patterns_are_english(string $phpPattern, string $icuPattern): void
    {
        $date = new \DateTime('2019-05-01 01:02:03.456', new \DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals($date->format($phpPattern), $formatter->formatPattern($date, $icuPattern));
    }

    /**
     * @dataProvider patterns
     */
    public function test_formats_objects(string $phpPattern, string $icuPattern): void
    {
        $date = new \DateTime('2019-05-01 01:02:03.456', new \DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals($date->format($phpPattern), $formatter->formatObject($date, $icuPattern));

        $calendar = \IntlCalendar::fromDateTime($date);
        $calendar->set(\IntlCalendar::FIELD_MILLISECOND, 456);

        $this->assertEquals($date->format($phpPattern), $formatter->formatObject($calendar, $icuPattern));
    }

    public function test_formats_objects_with_locale(): void
    {
        $date = new \DateTime('2019-05-01 01:02:03.456', new \DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter(DefaultLocale::fromString('en-US'));
        $this->assertEquals('01.05.2019, Mittwoch', $formatter->formatObject($date, 'dd.MM.yyyy, EEEE', 'de-DE'));
    }

    public function test_uses_default_locale(): void
    {
        $date = new \DateTime('2019-05-01 01:02:03.456', new \DateTimeZone('Europe/Berlin'));

        $formatter = new IntlDateTimeFormatter();
        $this->assertEquals('2019', $formatter->formatObject($date, 'yyyy'));
    }
}
