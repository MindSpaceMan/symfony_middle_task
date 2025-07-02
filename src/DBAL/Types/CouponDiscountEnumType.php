<?php
declare(strict_types=1);

namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Enum\CouponDiscountEnum;
class CouponDiscountEnumType extends Type
{
    public const NAME = 'coupon_discount_enum';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CouponDiscountEnum
    {
        return match (true) {
            $value === null                     => null,
            $value instanceof CouponDiscountEnum => $value,
            default                              => CouponDiscountEnum::from($value),
        };
    }

    /** @param string|CouponDiscountEnum|null $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CouponDiscountEnum ? $value->value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}