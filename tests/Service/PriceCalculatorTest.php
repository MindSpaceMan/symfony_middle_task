<?php

namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\CouponDiscountEnum;
use App\Service\PriceCalculatorService;
use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use PHPUnit\Framework\TestCase;
use Brick\Math\RoundingMode;
use Brick\Money\Money;

class PriceCalculatorTest extends TestCase
{
    private PriceCalculatorService $calculator;

    protected function setUp(): void
    {
        $this->calculator = new PriceCalculatorService();
    }

    /**
     * Check usual price calculation without coupon and tax
     * if taxNumber of unknown country - tax 0
     * Throw exception on this or not - decide on talk
     */
    public function testCalculatePrice_NoTaxNoCoupon(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Unknown tax number format: XX123');

        $product = (new Product())
            ->setName('TestProduct')
            ->setPrice(10000);

        $this->calculator->calculatePrice($product, null, 'XX123');
//        $this->assertSame(10000, $finalPrice->toInt(), 'Price should be same.');
    }

    /**
     * Germany check tax (19%)
     */
    public function testCalculatePrice_GermanyTax(): void
    {
        $product = (new Product())
            ->setName('TestProduct')
            ->setPrice(10000);

        $finalPrice = $this->calculator->calculatePrice($product, null, 'DE123456789');

        $this->assertSame(11900, $finalPrice->toInt(), 'Price must be 119€ (100€ + 19%).');
    }

    /**
     * Check fixed coupon (coupon = "D15") on product
     * + tax Italy (22%)
     * @throws RoundingNecessaryException
     * @throws DivisionByZeroException
     * @throws MathException
     * @throws NumberFormatException
     */
    public function testCalculatePrice_FixedDiscount_Italy(): void
    {
        $product = (new Product())
            ->setName('Iphone')
            ->setPrice(10000);

        // Фиксированная скидка 15 (евро)
        $coupon = (new Coupon())
            ->setCode('D15')
            ->setDiscountType(CouponDiscountEnum::FIXED)
            ->setValue(1500);

        $finalPrice = $this->calculator->calculatePrice($product, $coupon, 'IT12345678900');

        // 10000 - 1500 = 8500; tax IT (22%) = 8500 * 0.22 = 1870; summary: 8500 + 1870 = 10370.
        $this->assertSame(10370, $finalPrice->toInt(), 'Expected price: 10370.');
    }

    /**
     * Check percent coupon = "P10" + tax 24% Greece
     */
    public function testCalculatePrice_PercentDiscount_Greece(): void
    {
        $product = (new Product())->setName('Headphones')->setPrice(2000);

        $coupon = (new Coupon())->setCode('P10')->setDiscountType(CouponDiscountEnum::PERCENT)->setValue(1000);

        $finalPrice = $this->calculator->calculatePrice($product, $coupon, 'GR123456789');

        // 2000 * 0.9 = 1800; tax GR (24%) = 1800 * 0.24 = 432; summary: 1800 + 432 = 2232.
        $this->assertSame(2232, $finalPrice->toInt(), 'Expected price: 2232.');
    }

    /**
     * Check, that 100% discount price don't negative.
     */
    public function testCalculatePrice_100PercentCoupon(): void
    {
        $product = (new Product())->setName('Case')->setPrice(1000);

        $coupon = (new Coupon())->setCode('P100')->setDiscountType(CouponDiscountEnum::PERCENT)->setValue(10000);

        $finalPrice = $this->calculator->calculatePrice($product, $coupon, 'DE123456789');
        // 10 -> discount 100% -> 0, tax DE: 19% от 0 = 0
        $this->assertSame(0, $finalPrice->toInt(), 'Price after 100% discount must 0€.');
    }

    public function testCalculatePrice_UnknownTaxNumber(): void
    {
        $product = (new Product())->setName('Case')->setPrice(1000);

        $this->expectException(\DomainException::class);
        $this->calculator->calculatePrice($product, null, 'UNKNOWN');
    }
}
