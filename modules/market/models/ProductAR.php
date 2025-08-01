<?php // modules/market/models/ProductAR.php
namespace Market\models;

use yii\db\ActiveRecord;

final class ProductAR extends ActiveRecord
{
    public static function tableName(): string { return '{{%products}}'; }

    public function getImages() {
        return $this->hasMany(ProductImageAR::class, ['product_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }
}