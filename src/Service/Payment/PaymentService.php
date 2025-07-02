<?php
declare(strict_types=1);

namespace App\Service\Payment;

use App\Interface\PaymentProcessorInterface;

final readonly class PaymentService
{
    public function __construct(private PaymentProcessorInterface $paymentProcessor)
    {
    }

    public function pay(int $priceInCents): bool
    {
        return $this->paymentProcessor->pay($priceInCents);
    }
}
