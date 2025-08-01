<?php
// Application/OrderService.php  — фасад с методами из задания
declare(strict_types=1);

namespace Application;

use Domain\Entity\Order;
use Domain\Entity\OrderItem;
use Domain\Repository\OrderRepositoryInterface;
use Domain\ValueObject\Money;

final class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $repo,
        private OrderPrinter $printer = new OrderPrinter(),
        private OrderPresenter $presenter = new OrderPresenter(),
    ) {}

    // CRUD
    public function load(string $orderId): Order                     { return $this->repo->load($orderId); }
    public function save(Order $order): void                         { $this->repo->save($order); }
    public function update(Order $order): void                       { $this->repo->update($order); }
    public function delete(string $orderId): void                    { $this->repo->delete($orderId); }

    // Из задания
    public function addItem(string $orderId, array $item): void
    {
        $order = $this->load($orderId);
        $order->addItem(new OrderItem(
            sku:    $item['sku'],
            name:   $item['name'],
            qty:    (int)$item['qty'],
            price:  new Money((int)$item['price_minor'], $item['currency'] ?? 'RUB')
        ));
        $this->update($order);
    }

    public function deleteItem(string $orderId, string $sku): void
    {
        $order = $this->load($orderId);
        $order->deleteItem($sku);
        $this->update($order);
    }

    public function getItems(string $orderId): array                 { return $this->load($orderId)->getItems(); }
    public function getItemsCount(string $orderId): int              { return $this->load($orderId)->getItemsCount(); }
    public function calculateTotalSum(string $orderId): string       { return $this->load($orderId)->calculateTotalSum()->format(); }
    public function printOrder(string $orderId): string              { return $this->printer->print($this->load($orderId)); }
    public function showOrder(string $orderId): array                { return $this->presenter->show($this->load($orderId)); }
}