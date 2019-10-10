<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl;

use BinSoul\Common\I18n\DateTimeFormatter;
use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Locale;
use DateTimeInterface;
use IntlDateFormatter;

/**
 * Formats dates and times using the NumberFormatter class of the PHP intl extension.
 */
class IntlDateTimeFormatter implements DateTimeFormatter
{
    /**
     * @var string[]
     */
    private static $pattern = [
        '/(?<!y)yy(?!y)/' => 'y', // 4 digit year
        '/(?<!M)M(?!M)/' => 'MM', // 2 digit month
        '/(?<!d)d(?!d)/' => 'dd', // 2 digit day
        '/(?<!h)h(?!h)/' => 'hh', // 2 digit 12-hour-cycle
        '/(?<!H)H(?!H)/' => 'HH', // 2 digit 24-hour-cycle
        '/(?<!m)m(?!m)/' => 'mm', // 2 digit minute
        '/(?<!s)s(?!s)/' => 'ss', // 2 digit second
    ];

    /**
     * @var IntlDateFormatter[][]
     */
    private $formatters = [];

    /**
     * @var Locale
     */
    private $locale;

    /**
     * Constructs an instance of this class.
     *
     * @param Locale|null $locale
     */
    public function __construct(?Locale $locale = null)
    {
        if (!$locale) {
            $locale = DefaultLocale::fromString(\Locale::getDefault());
        }

        $this->locale = $locale;

        $types = [IntlDateFormatter::NONE, IntlDateFormatter::SHORT, IntlDateFormatter::MEDIUM];
        foreach ($types as $datetype) {
            foreach ($types as $timetype) {
                $formatter = new IntlDateFormatter($locale->getCode(), $datetype, $timetype);
                $pattern = $formatter->getPattern();
                $formatter->setPattern($this->expandNumbers($pattern));

                $this->formatters[$datetype][$timetype] = $formatter;
            }
        }
    }

    public function formatPattern(DateTimeInterface $datetime, string $pattern): string
    {
        $formatter = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::NONE];
        $formatter->setPattern($pattern);

        return $formatter->format($datetime);
    }

    public function formatTime(DateTimeInterface $time): string
    {
        return $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::SHORT]->format($time);
    }

    public function formatTimeWithSeconds(DateTimeInterface $time): string
    {
        return $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::MEDIUM]->format($time);
    }

    public function formatDate(DateTimeInterface $date): string
    {
        return $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::NONE]->format($date);
    }

    public function formatDateTime(DateTimeInterface $datetime): string
    {
        return $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::SHORT]->format($datetime);
    }

    public function formatDateTimeWithSeconds(DateTimeInterface $datetime): string
    {
        return $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::MEDIUM]->format($datetime);
    }

    public function withLocale(Locale $locale): DateTimeFormatter
    {
        if ($locale->getCode() === $this->locale->getCode()) {
            return $this;
        }

        return new self($locale);
    }

    /**
     * @param object      $object
     * @param mixed       $format
     * @param string|null $locale
     *
     * @return string
     */
    public function formatObject($object, $format = null, $locale = null): string
    {
        return $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::NONE]::formatObject($object, $format, $locale ?? $this->locale->getCode());
    }

    /**
     * Converts patterns with one digit numbers to at least 2 digits.
     *
     * @param string $pattern
     *
     * @return string
     */
    private function expandNumbers(string $pattern): string
    {
        $result = $pattern;
        foreach (self::$pattern as $search => $replace) {
            $replaced = preg_replace($search, $replace, $result);
            if ($replaced !== null) {
                $result = $replaced;
            }
        }

        return $result;
    }
}
