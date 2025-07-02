<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\CouponDiscountEnum;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'coupon_discount_enum')]
    #[Assert\Choice(callback: [CouponDiscountEnum::class, 'values'])]
    private ?CouponDiscountEnum $discountType = null;

    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDiscountType(): ?CouponDiscountEnum
    {
        return $this->discountType;
    }

    public function setDiscountType(CouponDiscountEnum $discountType): self
    {
        $this->discountType = $discountType;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
