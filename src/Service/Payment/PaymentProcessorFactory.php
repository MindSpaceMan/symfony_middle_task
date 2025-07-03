<?php
declare(strict_types=1);

namespace App\Service\Payment;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use App\Enum\PaymentProcessorEnum;
use App\Interface\PaymentProcessorInterface;

final readonly class PaymentProcessorFactory
{
    public function __construct(
        #[TaggedLocator(tag: 'app.payment_processor', indexAttribute: 'alias')]
        private ContainerInterface $locator
    ) {}

    public function getProcessor(PaymentProcessorEnum $enum): PaymentProcessorInterface
    {
        $key = $enum->value;
        if (!$this->locator->has($key)) {
            throw new UnprocessableEntityHttpException("Unknown payment processor: $key");
        }
        /** @var PaymentProcessorInterface */
        return $this->locator->get($key);
    }
}
