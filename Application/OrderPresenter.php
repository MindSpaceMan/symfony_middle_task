<?php
// Application/OrderPresenter.php
declare(strict_types=1);

namespace Application;

use Domain\Entity\Order;

final class OrderPresenter
{
    public function show(Order $order): array
    {
        return [
            'id'     => $order->id,
            'status' => $order->status->value,
            'items'  => array_map(fn($i) => [
                'sku'      => $i->sku,
                'name'     => $i->name,
                'qty'      => $i->qty,
                'price'    => $i->price->format(),
                'subtotal' => $i->subtotal()->format(),
            ], $order->getItems()),
            'total'  => $order->calculateTotalSum()->format(),
            'count'  => $order->getItemsCount(),
        ];
    }
}