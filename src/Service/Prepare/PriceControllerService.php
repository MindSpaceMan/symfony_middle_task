<?php
declare(strict_types=1);

namespace App\Service\Prepare;

use App\Controller\ResponseDTO\CalculatePriceResponse;
use App\DTO\CalculatePriceRequest;
use App\DTO\PurchaseRequest;
use App\Service\Entities\CouponService;
use App\Service\Entities\ProductService;
use App\Service\Money\MoneyFormatter;
use App\Service\Payment\PaymentProcessorFactory;
use App\Service\PriceCalculatorService;
use Symfony\Component\HttpFoundation\Response;

final readonly class PriceControllerService
{

    public function __construct(
        private ProductService          $productService,
        private CouponService           $couponService,
        private PriceCalculatorService  $calculatorService,
        private MoneyFormatter          $moneyFormatter,
        private PaymentProcessorFactory $paymentFactory
    )
    {
    }

    public function calculatePrice(CalculatePriceRequest $request): int
    {
        $product = $this->productService->getProduct($request->productId);

        $coupon = $this->couponService->getCoupon($request->couponCode);

        $finalPrice = $this->calculatorService->calculatePrice($product, $coupon, $request->taxNumber);
        return $this->moneyFormatter->format($finalPrice);
    }

    public function purchasePrice(PurchaseRequest $request): array
    {
        $product = $this->productService->getProduct($request->productId);

        $coupon = $this->couponService->getCoupon($request->couponCode);

        $finalPrice = $this->calculatorService->calculatePrice($product, $coupon, $request->taxNumber);

        try {
            $processorEnum = PaymentProcessor::from($request->paymentProcessor);
        } catch (\ValueError $e) {
            throw new UnprocessableEntityHttpException("Unknown payment processor: {$request->paymentProcessor}");
        }

        $paymentService = $this->paymentFactory->getProcessor($processorEnum);

        if (!$paymentService->pay($finalPrice->toInt())) {
            return [
                'json' => ['errors' => ['payment' => 'Payment failed']],
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }
        return
            [
                'json' => [
                    'status' => 'success',
                    'finalPrice' => $this->moneyFormatter->format($finalPrice)
                ],
                'code' => Response::HTTP_OK,
            ];
    }
}