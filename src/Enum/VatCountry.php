<?php

namespace App\Enum;

use Brick\Math\BigDecimal;
enum VatCountry: string
{
    case DE = 'DE';
    case IT = 'IT';
    case FR = 'FR';
    case GR = 'GR';

    /** Pattern VAT-numbers for country */
    public function pattern(): string
    {
        return match ($this) {
            self::DE => '/^DE\d{9}$/',            // Germany
            self::IT => '/^IT\d{11}$/',           // Italy
            self::GR => '/^GR\d{9}$/',            // Greece
            self::FR => '/^FR[a-zA-Z]{2}\d{9}$/', // France (letters + numbers)
        };
    }

    /** VAT rate for the country */
    public function rate(): BigDecimal
    {
        return match ($this) {
            self::DE => BigDecimal::of('0.19'),
            self::IT => BigDecimal::of('0.22'),
            self::FR => BigDecimal::of('0.20'),
            self::GR => BigDecimal::of('0.24'),
        };
    }

    /** "Soft" determination of the country by VAT number */
    public static function tryFromVat(string $vat): ?VatCountry
    {
        foreach (self::cases() as $case) {
            if (\preg_match($case->pattern(), $vat)) {
                return $case;
            }
        }
        return null;
    }

    /** "Hard" definition of country by VAT number */
    public static function fromVat(string $vat): self
    {
        return self::tryFromVat($vat)
            ?? throw new \DomainException("Unknown tax number format: $vat");
    }
}