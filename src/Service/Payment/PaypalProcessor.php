<?php
declare(strict_types=1);

namespace App\Service\Payment;

use App\Enum\PaymentProcessorEnum;
use App\Interface\PaymentProcessorInterface;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use App\Attribute\AsPaymentProcessor;


#[AsPaymentProcessor('paypal')]
final readonly class PaypalProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $paypalProcessor,
        private LoggerInterface $logger,
    )
    {}

    /**
     * @param int $priceInCents
     * @return bool true, если оплата успешно, false — если исключение
     */
    public function pay(int $priceInCents): bool
    {
        try {
            $this->paypalProcessor->pay($priceInCents);
            return true;
        } catch (\Exception $e) {
            $this->logger->error("Payment failed: " . $e->getMessage());
            return false;
        }
    }

    public function getAlias(): string
    {
        return PaymentProcessorEnum::PAYPAL->value;
    }
}
