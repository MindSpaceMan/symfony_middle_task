<?php
declare(strict_types=1);

namespace App\Controller\ResponseDTO;

use Attribute;
use OpenApi\Attributes as OA;

/**
 * Wrapper attribute for /calculate-price end-point
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class CalculatePriceResponse extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            description: 'Calculates product price in cents',
            summary: 'Calculates product price with the possible coupon and tax',

            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Final price in cents ',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'finalPrice',
                                type: 'number',
                                format: 'int64',
                                example: 3000
                            )
                            ,
                        ],
                    ),
                ),
                new OA\Response(response: 422, description: 'Entity not found: invalid input data'),
            ],
        );
    }
}