<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\Product;
use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\ValidTaxNumber;

final readonly class CalculatePriceRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[DoctrineEntity(entity: Product::class, idField: 'id', message: 'Product not found')]
        #[Property(example: '1553a45e-f94c-412c-8ef6-a5d27f026e0d')]
        public string  $productId,

        #[Assert\NotBlank]
        #[ValidTaxNumber]
        #[Property(example: 'IT12345678900')]
        public string  $taxNumber,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Type('string')]
        #[DoctrineEntity(entity: Coupon::class, idField: 'code', message: 'Coupon not found', ignoreNull: true)]
        #[Property(example: 'D15')]
        public ?string $couponCode = null
    )
    {
    }

}
