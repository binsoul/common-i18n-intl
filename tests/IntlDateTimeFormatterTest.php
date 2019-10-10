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
        $this->assertEquals('2019-02-03', $formatter->formatPattern(new DateTime('2019-02-03'), 'YYYY-MM-dd'));
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
}
