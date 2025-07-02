<?php
declare(strict_types=1);

namespace App\Service\Entities;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final readonly class ProductService
{
    public function __construct(private ProductRepository $repository) {}

    /**
     * Get product
     */
    public function getProduct(string $productId): Product
    {
        return $this->repository->find($productId);
    }
}