<?php // modules/market/models/UserFavoriteAR.php
namespace Market\models;

use yii\db\ActiveRecord;

final class UserFavoriteAR extends ActiveRecord
{
    public static function tableName(): string { return '{{%user_favorites}}'; }
}