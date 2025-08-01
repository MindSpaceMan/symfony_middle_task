<?php
// Domain/Entity/Order.php
declare(strict_types=1);

namespace Domain\Entity;

use Domain\Enum\OrderStatus;
use Domain\ValueObject\Money;

final class Order
{
    /** @var array<string,OrderItem> */
    private array $items = [];

    public function __construct(
        public readonly string $id,
        public OrderStatus $status = OrderStatus::DRAFT,
        private string $currency = 'RUB',
    ) {}

    /** командные операции над агрегатом */
    public function addItem(OrderItem $item): void
    {
        if ($item->price->currency !== $this->currency) {
            throw new \DomainException('Order currency mismatch');
        }
        if (isset($this->items[$item->sku])) {
            $this->items[$item->sku]->qty += $item->qty;
            return;
        }
        $this->items[$item->sku] = $item;
    }

    public function deleteItem(string $sku): void
    {
        unset($this->items[$sku]);
    }

    /** запросы к агрегату */
    /** @return OrderItem[] */
    public function getItems(): array { return array_values($this->items); }

    public function getItemsCount(): int
    {
        $sum = 0;
        foreach ($this->items as $i) $sum += $i->qty;
        return $sum;
    }

    public function calculateTotalSum(): Money
    {
        $total = Money::zero($this->currency);
        foreach ($this->items as $i) $total = $total->add($i->subtotal());
        return $total;
    }
}