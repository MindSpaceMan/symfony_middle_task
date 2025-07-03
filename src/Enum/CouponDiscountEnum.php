<?php
declare(strict_types=1);

namespace App\Enum;

enum CouponDiscountEnum: string
{
    case FIXED = 'fixed';
    case PERCENT = 'percent';

    public static function values(): array
    {
        return array_map(fn(self $processor) => $processor->value, self::cases());
    }
}
