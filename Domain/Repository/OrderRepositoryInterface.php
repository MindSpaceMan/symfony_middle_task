<?php
// Domain/Repository/OrderRepositoryInterface.php
declare(strict_types=1);

namespace Domain\Repository;

use Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function load(string $orderId): Order;    // 404 → исключение
    public function save(Order $order): void;        // create
    public function update(Order $order): void;      // update
    public function delete(string $orderId): void;   // delete
}