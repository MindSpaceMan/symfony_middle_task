<?php
declare(strict_types=1);

namespace App\Service\Entities;

use App\Entity\Product;
use App\Repository\ProductRepository;

final readonly class ProductService
{
    public function __construct(private ProductRepository $repository) {}

    /**
     * Get product
     */
    public function getProduct(string $product): Product
    {
        return $this->repository->find($product);
    }
}