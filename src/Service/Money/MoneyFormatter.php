<?php

declare(strict_types=1);

namespace App\Service\Money;

use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;

final readonly class MoneyFormatter
{
    /**
     * Formatting `Money` to `int`
     * @throws MathException
     */
    public function format(BigDecimal $money): int
    {
        return $money->toInt();
    }
}
