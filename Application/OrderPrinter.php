<?php
// Application/OrderPrinter.php
declare(strict_types=1);

namespace Application;

use Domain\Entity\Order;

final class OrderPrinter
{
    public function print(Order $order): string
    {
        $lines = ["ORDER #{$order->id} | status={$order->status->value}"];
        foreach ($order->getItems() as $i) {
            $lines[] = sprintf('- %s x%d @ %s = %s',
                $i->name, $i->qty, $i->price->format(), $i->subtotal()->format()
            );
        }
        $lines[] = 'TOTAL: ' . $order->calculateTotalSum()->format();
        return implode(PHP_EOL, $lines);
    }
}