<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl;

use BinSoul\Common\I18n\DateTimeFormatter;
use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Locale;
use DateTimeInterface;
use IntlCalendar;
use IntlDateFormatter;

/**
 * Formats dates and times using the NumberFormatter class of the PHP intl extension.
 */
class IntlDateTimeFormatter implements DateTimeFormatter
{
    public const string ATOM = "yyyy-MM-dd'T'HH:mm:ssxxx";

    public const string COOKIE = 'eeee, dd-MMM-yyyy HH:mm:ss zzz';

    public const string ISO8601_FULL = "yyyy-MM-dd'T'HH:mm:ssxxx";

    public const string ISO8601_DATE = 'yyyy-MM-dd';

    public const string ISO8601_TIME = 'HH:mm:ss';

    public const string ISO8601_DATETIME = "yyyy-MM-dd'T'HH:mm:ss";

    public const string RFC822 = 'eee, dd MMM yy HH:mm:ss xx';

    public const string RFC850 = 'eeee, dd-MMM-yy HH:mm:ss zzz';

    public const string RFC1036 = 'eee, dd MMM yy HH:mm:ss xx';

    public const string RFC1123 = 'eee, dd MMM yyyy HH:mm:ss xx';

    public const string RFC2822 = 'eee, dd MMM yyyy HH:mm:ss xx';

    public const string RFC3339 = "yyyy-MM-dd'T'HH:mm:ssxxx";

    public const string RFC3339_EXTENDED = "yyyy-MM-dd'T'HH:mm:ss.SSSxxx";

    public const string RFC7231 = "eee, dd MMM yyyy HH:mm:ss 'GMT'";

    public const string RSS = 'eee, dd MMM yyyy HH:mm:ss xx';

    public const string W3C = "yyyy-MM-dd'T'HH:mm:ssxxx";

    /**
     * @var string[]
     */
    private static array $knownPatterns = [
        self::ATOM,
        self::COOKIE,
        self::ISO8601_FULL,
        self::ISO8601_DATE,
        self::ISO8601_TIME,
        self::ISO8601_DATETIME,
        self::RFC822,
        self::RFC850,
        self::RFC1036,
        self::RFC1123,
        self::RFC2822,
        self::RFC3339,
        self::RFC3339_EXTENDED,
        self::RFC7231,
        self::RSS,
        self::W3C,
    ];

    /**
     * @var string[]
     */
    private static array $expansionPatterns = [
        '/(?<!y)yy(?!y)/' => 'y', // 4 digit year
        '/(?<!M)M(?!M)/' => 'MM', // 2 digit month
        '/(?<!d)d(?!d)/' => 'dd', // 2 digit day
        '/(?<!h)h(?!h)/' => 'hh', // 2 digit 12-hour-cycle
        '/(?<!H)H(?!H)/' => 'HH', // 2 digit 24-hour-cycle
        '/(?<!m)m(?!m)/' => 'mm', // 2 digit minute
        '/(?<!s)s(?!s)/' => 'ss', // 2 digit second
    ];

    /**
     * @var string[]
     */
    private static array $phpToIcu = [
        'd' => 'dd',
        'D' => 'eee',
        'j' => 'd',
        'l' => 'eeee',
        'N' => 'e',
        'S' => '',
        'w' => '',
        'z' => 'D',
        'W' => 'w',
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        't' => '',
        'L' => '',
        'o' => 'Y',
        'Y' => 'yyyy',
        'y' => 'yy',
        'a' => 'a',
        'A' => 'a',
        'B' => '',
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'u' => '',
        'e' => 'VV',
        'I' => '',
        'O' => 'xx',
        'P' => 'xxx',
        'T' => 'zzz',
        'Z' => '',
        'c' => "yyyy-MM-dd'T'HH:mm:ssxxx",
        'r' => 'eee, dd MMM yyyy HH:mm:ss xx',
        'U' => '',
        'v' => 'SSS',
    ];

    /**
     * @var IntlDateFormatter[][]
     */
    private array $formatters = [];

    private readonly Locale $locale;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?Locale $locale = null)
    {
        $this->locale = $locale ?? DefaultLocale::fromString(\Locale::getDefault());

        $types = [IntlDateFormatter::NONE, IntlDateFormatter::SHORT, IntlDateFormatter::MEDIUM];

        foreach ($types as $datetype) {
            foreach ($types as $timetype) {
                $formatter = new IntlDateFormatter($this->locale->getCode(), $datetype, $timetype);
                $pattern = $formatter->getPattern();

                if ($pattern !== false) {
                    $formatter->setPattern($this->expandNumbers($pattern));
                }

                $this->formatters[$datetype][$timetype] = $formatter;
            }
        }
    }

    public function formatPattern(DateTimeInterface $datetime, string $pattern): string
    {
        $formatter = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::NONE];
        $format = $this->injectTimeZone($datetime, $pattern);
        $formatter->setPattern($format);

        if ($this->isKnownPattern($pattern)) {
            $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::NONE]::formatObject($datetime, $format, 'en');

            return $result !== false ? $result : '';
        }

        $formatter->setTimeZone($datetime->getTimezone());

        $result = $formatter->format($datetime);

        return $result !== false ? $result : '';
    }

    public function formatTime(DateTimeInterface $time): string
    {
        $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::SHORT]->setTimeZone($time->getTimezone());

        $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::SHORT]->format($time);

        return $result !== false ? $result : '';
    }

    public function formatTimeWithSeconds(DateTimeInterface $time): string
    {
        $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::MEDIUM]->setTimeZone($time->getTimezone());

        $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::MEDIUM]->format($time);

        return $result !== false ? $result : '';
    }

    public function formatDate(DateTimeInterface $date): string
    {
        $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::NONE]->setTimeZone($date->getTimezone());

        $result = $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::NONE]->format($date);

        return $result !== false ? $result : '';
    }

    public function formatDateTime(DateTimeInterface $datetime): string
    {
        $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::SHORT]->setTimeZone($datetime->getTimezone());

        $result = $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::SHORT]->format($datetime);

        return $result !== false ? $result : '';
    }

    public function formatDateTimeWithSeconds(DateTimeInterface $datetime): string
    {
        $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::MEDIUM]->setTimeZone($datetime->getTimezone());

        $result = $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::MEDIUM]->format($datetime);

        return $result !== false ? $result : '';
    }

    public function withLocale(Locale $locale): DateTimeFormatter
    {
        if ($locale->getCode() === $this->locale->getCode()) {
            return $this;
        }

        return new self($locale);
    }

    /**
     * Formats an object.
     *
     * @param string|array<int, int>|int|null $pattern
     */
    public function formatObject(DateTimeInterface|IntlCalendar $object, string|int|array|null $pattern = null, ?string $locale = null): string
    {
        $format = $pattern;

        if (is_string($pattern)) {
            if ($object instanceof DateTimeInterface) {
                $format = $this->injectTimeZone($object, $pattern);
            } else {
                $format = $this->injectTimeZone($object->toDateTime(), $pattern);
            }

            if ($this->isKnownPattern($pattern)) {
                $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::NONE]::formatObject($object, $format, 'en');

                return $result !== false ? $result : '';
            }
        }

        $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::NONE]::formatObject($object, $format, $locale ?? $this->locale->getCode());

        return $result !== false ? $result : '';
    }

    /**
     * Returns the pattern used to format times.
     */
    public function getTimePattern(): string
    {
        $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::SHORT]->getPattern();

        return $result !== false ? $result : '';
    }

    /**
     * Returns the pattern used to format times including seconds.
     */
    public function getTimeWithSecondsPattern(): string
    {
        $result = $this->formatters[IntlDateFormatter::NONE][IntlDateFormatter::MEDIUM]->getPattern();

        return $result !== false ? $result : '';
    }

    /**
     * Returns the pattern used to format dates.
     */
    public function getDatePattern(): string
    {
        $result = $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::NONE]->getPattern();

        return $result !== false ? $result : '';
    }

    /**
     * Returns the pattern used to format datetimes.
     */
    public function getDateTimePattern(): string
    {
        $result = $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::SHORT]->getPattern();

        return $result !== false ? $result : '';
    }

    /**
     * Returns the pattern used to format datetimes including seconds.
     */
    public function getDateTimeWithSecondsPattern(): string
    {
        $result = $this->formatters[IntlDateFormatter::SHORT][IntlDateFormatter::MEDIUM]->getPattern();

        return $result !== false ? $result : '';
    }

    /**
     * Converts the given PHP date pattern to an ICU pattern.
     */
    public static function convertToIcu(string $pattern): string
    {
        $parts = preg_split('/(\\\\.)/', $pattern, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if ($parts === false) {
            return $pattern;
        }

        $result = '';
        $isEscaped = false;

        foreach ($parts as $part) {
            if ($part[0] === '\\') {
                if ($isEscaped) {
                    $result .= $part[1];
                } else {
                    $result .= "'" . $part[1];
                }

                $isEscaped = true;
            } else {
                if ($isEscaped) {
                    $result .= "'";
                    $isEscaped = false;
                }

                $result .= strtr($part, self::$phpToIcu);
            }
        }

        if ($isEscaped) {
            $result .= "'";
        }

        return $result;
    }

    /**
     * Converts patterns with one digit numbers to at least 2 digits.
     */
    private function expandNumbers(string $pattern): string
    {
        $result = $pattern;

        foreach (self::$expansionPatterns as $search => $replace) {
            $replaced = preg_replace($search, $replace, $result);

            if ($replaced !== null) {
                $result = $replaced;
            }
        }

        return $result;
    }

    /**
     * Replaces GMT+Offset with the short name of the time zone.
     */
    private function injectTimeZone(DateTimeInterface $datetime, string $pattern): string
    {
        $result = $pattern;

        if (str_contains($pattern, 'z')) {
            $result = preg_replace('/(?<!z)z{1,3}(?!z)/', "'" . $datetime->format('T') . "'", $pattern);

            if ($result === null) {
                return $pattern;
            }
        }

        return $result;
    }

    /**
     * Detects if the pattern is known.
     */
    private function isKnownPattern(string $pattern): bool
    {
        return in_array($pattern, self::$knownPatterns, true);
    }
}
