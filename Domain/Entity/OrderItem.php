<?php
// Domain/Entity/OrderItem.php
declare(strict_types=1);

namespace Domain\Entity;

use Domain\ValueObject\Money;

final class OrderItem
{
    public function __construct(
        public readonly string $sku,
        public string $name,
        public int $qty,
        public Money $price
    ) {
        if ($this->qty < 1) throw new \InvalidArgumentException('qty >= 1');
    }

    public function subtotal(): Money { return $this->price->mul($this->qty); }
}