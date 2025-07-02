<?php
declare(strict_types=1);

namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Enum\CouponDiscountEnum;
class CouponDiscountEnumType extends Type
{
    public const string NAME = 'coupon_discount_enum';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'coupon_discount_enum';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CouponDiscountEnum
    {
        return $value !== null ? CouponDiscountEnum::from($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CouponDiscountEnum ? $value->value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}