<?php
declare(strict_types=1);

namespace App\Service\Entities;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final readonly class CouponService
{
    public function __construct(private CouponRepository $repository) {}

    /**
     * Get coupon, exception to not found
     */
    public function getCoupon(?string $couponCode): ?Coupon
    {
        if (!$couponCode) {
            return null;
        }

        return $this->repository->findOneBy(['code' => $couponCode])
            ?? throw new UnprocessableEntityHttpException("Coupon not found: $couponCode");
    }
}
