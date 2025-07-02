<?php
declare(strict_types=1);

namespace App\Controller\ResponseDTO;

use Attribute;
use OpenApi\Attributes as OA;

/**
 * Wrapper attribute for /calculate-price end-point
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class PurchaseResponse extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            summary: 'Осуществляет покупку продукта с проведением оплаты',

            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Покупка успешно завершена',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'status',
                                type: 'string',
                                example: 'success'
                            ),
                            new OA\Property(
                                property: 'finalPrice',
                                type: 'number',
                                format: 'int64',
                                example: 2900
                            ),
                        ],
                    ),
                ),
                new OA\Response(response: 422, description: 'Payment error or invalid input data'),
            ],
        );
    }
}