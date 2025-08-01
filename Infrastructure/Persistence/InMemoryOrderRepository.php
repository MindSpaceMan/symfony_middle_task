<?php
// Infrastructure/Persistence/InMemoryOrderRepository.php (для тестов/демо)
declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Entity\Order;
use Domain\Repository\OrderRepositoryInterface;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /** @var array<string,Order> */
    private array $storage = [];

    public function load(string $orderId): Order {
        return $this->storage[$orderId] ?? throw new \RuntimeException("Order $orderId not found");
    }
    public function save(Order $order): void   { $this->storage[$order->id] = $order; }
    public function update(Order $order): void { $this->save($order); }
    public function delete(string $orderId): void { unset($this->storage[$orderId]); }
}