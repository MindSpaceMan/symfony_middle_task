<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\ResponseDTO\CalculatePriceResponse;
use App\Controller\ResponseDTO\PurchaseResponse;
use App\DTO\CalculatePriceRequest;
use App\DTO\PurchaseRequest;
use App\Service\Prepare\PriceControllerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class PriceController extends AbstractController
{
    public function __construct(
        private readonly PriceControllerService $priceControllerService
    )
    {
    }

    #[CalculatePriceResponse]
    #[Route('/calculate-price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload] CalculatePriceRequest $request
    ): JsonResponse
    {
        return $this->json(
            [
                'finalPrice' => $this->priceControllerService->calculateProductPrice($request),
            ],
            Response::HTTP_OK
        );
    }

    #[PurchaseResponse]
    #[Route('/purchase', methods: ['POST'])]
    public function purchase(
        #[MapRequestPayload] PurchaseRequest $request
    ): JsonResponse
    {
        $purchaseResult = $this->priceControllerService->purchaseProduct($request);
        return $this->json(
            $purchaseResult['json'],
            $purchaseResult['code']
        );
    }
}
