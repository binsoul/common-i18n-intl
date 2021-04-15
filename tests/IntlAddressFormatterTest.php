<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl;

use BinSoul\Common\I18n\DefaultAddress;
use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\IntlAddressFormatter;
use PHPUnit\Framework\TestCase;

class IntlAddressFormatterTest extends TestCase
{
    public function countriesAndNames(): array
    {
        return [
            ['DE', 'en', 'Germany'],
            ['DE', 'fr', 'Allemagne'],
            ['DE', 'de', 'Deutschland'],
            ['IT', 'en', 'Italy'],
            ['IT', 'fr', 'Italie'],
            ['IT', 'de', 'Italien'],
            ['FR', 'en', 'France'],
            ['FR', 'fr', 'France'],
            ['FR', 'de', 'Frankreich'],
        ];
    }

    /**
     * @dataProvider countriesAndNames
     */
    public function test_generates_localized_country_names(string $countryCode, string $localeCode, string $countryName): void
    {
        $formatter = new IntlAddressFormatter(DefaultLocale::fromString($localeCode));
        $address = $this->buildAddress($countryCode);

        $formattedAddress = $formatter->format($address);
        $lines = explode("\n", $formattedAddress);
        self::assertEquals($countryName, $lines[count($lines) - 1]);
    }

    private function buildAddress(string $countryCode): DefaultAddress
    {
        return new DefaultAddress(
            'organization',
            'namePrefix',
            'firstname',
            'lastname',
            'addressLine1',
            'addressLine2',
            'addressLine3',
            'sortingCode',
            'postalCode',
            'locality',
            'dependentLocality',
            'adminArea',
            $countryCode
        );
    }
}
