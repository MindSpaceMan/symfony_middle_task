<?php // modules/market/models/ProductImageAR.php
namespace Market\models;

use yii\db\ActiveRecord;

final class ProductImageAR extends ActiveRecord
{
    public static function tableName(): string { return '{{%product_images}}'; }
}