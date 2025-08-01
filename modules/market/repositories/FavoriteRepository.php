<?php // modules/market/repositories/FavoriteRepository.php
namespace Market\repositories;

use Market\models\UserFavoriteAR;

final class FavoriteRepository
{
    public function add(int $userId, int $productId): void
    {
        if (UserFavoriteAR::find()->where(compact('userId','productId'))->exists()) return;
        $m = new UserFavoriteAR(['user_id'=>$userId,'product_id'=>$productId]);
        if (!$m->save()) throw new \RuntimeException('favorite add failed');
    }
    public function remove(int $userId, int $productId): void
    {
        UserFavoriteAR::deleteAll(compact('userId','productId'));
    }
}