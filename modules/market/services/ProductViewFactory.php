<?php // modules/market/services/ProductViewFactory.php
namespace Market\services;

use Market\models\ProductAR;

final class ProductViewFactory
{
    public function __construct(private ImageUrlResolver $resolver) {}

    public function create(ProductAR $p, ?array $favMap = null): \Market\dto\ProductView
    {
        $v = new \Market\dto\ProductView();
        $v->id = (int)$p->id;
        $v->name = (string)$p->name;
        $v->description = (string)$p->description;
        $v->category = (string)$p->category;

        $urls = [];
        foreach ($p->images as $img) {
            $urls[] = $this->resolver->bySource($img->source)->url($img->path);
        }
        $urls = array_values(array_filter($urls));
        if ($urls) {
            $v->images = $urls;          // новое поле
            $v->image_url = $urls[0];    // обратная совместимость
        }

        if ($favMap !== null) {
            $v->is_favorite = isset($favMap[$p->id]);
        }
        return $v;
    }
}