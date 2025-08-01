<?php

return [
    // config/web.php
    'components' => [/* ... */],
    'controllerMap' => [
        'products' => Market\controllers\ProductsController::class,
        'favorites' => Market\controllers\FavoritesController::class,
    ],
// GET /products, GET /products/<id>
// POST /favorites/<productId>, DELETE /favorites/<productId>, GET /favorites
];