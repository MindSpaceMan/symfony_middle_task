<?php
// Domain/Enum/OrderStatus.php
declare(strict_types=1);

namespace Domain\Enum;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case PLACED = 'placed';
    case PAID = 'paid';
    case CANCELED = 'canceled';
}