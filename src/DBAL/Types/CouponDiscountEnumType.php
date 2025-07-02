<?php
declare(strict_types=1);

namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Enum\CouponDiscountEnum;
class CouponDiscountEnumType extends Type
{
    public const NAME = 'coupon_discount_enum';

    /* ---------- DDL declaration ---------- */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::NAME;
    }

    /* ---------- PHP ← DB ---------- */
    /** @param string|CouponDiscountEnum|null $value */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CouponDiscountEnum
    {
        return match (true) {
            $value === null                      => null,             // null → null
            $value instanceof CouponDiscountEnum => $value,           // уже enum
            default                              => CouponDiscountEnum::from($value),  // строка → enum
        };
    }

    /* ---------- DB ← PHP ---------- */
    /** @param string|CouponDiscountEnum|null $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CouponDiscountEnum ? $value->value : $value;
    }

    /* ---------- little things for SchemaTool & PDO ---------- */
    public function getName(): string
    {
        return self::NAME;
    }

    // To prevent Doctrine from creating extra ALTER TABLE statements when diffing
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    // How to bind a parameter in PDO (string)
    public function getBindingType(): int
    {
        return ParameterType::STRING;
    }
}