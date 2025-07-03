<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enum\PaymentProcessorEnum;
use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\ValidTaxNumber;
use App\Entity\Product;
use App\Entity\Coupon;
use Happyr\Validator\Constraint\EntityExist;

final readonly class PurchaseRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[EntityExist(entity: Product::class, property: 'id', message: 'Product not found')]
        #[Property(example: '1553a45e-f94c-412c-8ef6-a5d27f026e0d')]
        public string $product,

        #[Assert\NotBlank]
        #[ValidTaxNumber]
        #[Property(example: 'IT12345678900')]
        public string $taxNumber,

        #[Assert\NotBlank]
        #[Assert\Choice(callback: [PaymentProcessorEnum::class, 'values'], message: 'Unknown payment processor')]

        #[Property(example: 'paypal|stripe')]
        public string $paymentProcessor,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Type('string')]
        #[EntityExist(entity: Coupon::class, property: 'code', message: 'Coupon not found')]
        #[Property(example: 'D15')]
        public ?string $couponCode = null,

    )
    {
    }
}
