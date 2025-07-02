<?php

namespace App\Interface;

interface PaymentProcessorInterface
{
    public function getAlias(): string;
    public function pay(int $priceInCents): bool;
}
