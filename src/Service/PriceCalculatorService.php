<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\CouponDiscountEnum;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use DomainException;
use App\Entity\Product;
use App\Entity\Coupon;
use App\Enum\VatCountry;

final readonly class PriceCalculatorService
{
    /**
     * Calculates final price of product with the coupon and tax - if exists.
     *
     * @param Product $product
     * @param Coupon|null $coupon
     * @param string $vatNumber
     * @return BigDecimal
     * @throws DivisionByZeroException
     * @throws NumberFormatException
     * @throws RoundingNecessaryException
     */
    public function calculatePrice(Product $product, ?Coupon $coupon, string $vatNumber): BigDecimal
    {
        $basePriceInCents = BigDecimal::of($product->getPrice());

        if ($coupon) {
            $basePriceInCents = $this->applyCoupon($basePriceInCents, $coupon);
        }

        $country = VatCountry::fromVat($vatNumber);

        // Рассчитываем налог: налог = базовая цена * ставка налога (с округлением)
        // Финальная цена = базовая цена + налог
        return $this->addVat($basePriceInCents, $country);
    }

    private function addVat(BigDecimal $basePriceInCents, VatCountry $country): BigDecimal
    {
        $vatAmount = $basePriceInCents
            ->multipliedBy($country->rate())
            ->toScale(0, RoundingMode::HALF_UP);

        return $basePriceInCents->plus($vatAmount);
    }

    private function applyCoupon(BigDecimal $price, ?Coupon $coupon): BigDecimal
    {
        if ($coupon === null) {
            return $price;
        }

        return match ($coupon->getDiscountType()) {
            CouponDiscountEnum::FIXED   => $this->applyFixedDiscount(
                $price,
                $coupon->getValue()
            ),

            CouponDiscountEnum::PERCENT => $this->applyPercentageDiscount(
                $price,
                $coupon->getValue()
            ),
            default => throw new DomainException("Unknown discount type: {$coupon->getDiscountType()->value}")
        };
    }

    /**
     * Применяет фиксированную скидку.
     *
     * @param BigDecimal $price Базовая цена в минорных единицах.
     * @param int $discountValue Фиксированная скидка в минорных единицах.
     *
     * @return BigDecimal
     * @throws DivisionByZeroException
     * @throws MathException
     * @throws NumberFormatException
     * @throws RoundingNecessaryException
     */
    private function applyFixedDiscount(BigDecimal $price, int $discountValue): BigDecimal
    {
        $result = $price->minus(BigDecimal::of($discountValue));
        // if result less than 0, return 0.
        return $result->isLessThan(BigDecimal::of(0)) ? BigDecimal::of(0) : $result;
    }

    private function applyPercentageDiscount(BigDecimal $price, int $discountBp): BigDecimal
    {
        if ($discountBp < 0 || $discountBp > 10_000) {
            throw new \DomainException('Discount percentage must be between 0% and 100%.');
        }

        // net = price * (10000 - bp) / 10000
        $net = $price
            ->multipliedBy(10_000 - $discountBp)
            ->dividedBy(10_000, 0, RoundingMode::HALF_UP); // scale 0 → копейки

        // protection against negative value (in case of fp errors)
        return $net->isLessThan(BigDecimal::zero()) ? BigDecimal::zero() : $net;
    }
}
