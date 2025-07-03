<?php
declare(strict_types=1);

namespace App\Service\Entities;

use App\Entity\Coupon;
use App\Repository\CouponRepository;

final readonly class CouponService
{
    public function __construct(private CouponRepository $repository) {}

    /**
     * Get coupon
     */
    public function getCoupon(?string $couponCode): ?Coupon
    {
        if (!$couponCode) {
            return null;
        }

        return $this->repository->findOneBy(['code' => $couponCode]);
    }
}
