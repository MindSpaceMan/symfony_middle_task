<?php // modules/market/repositories/ProductReadRepository.php
namespace Market\repositories;

use Market\models\ProductAR;
use Market\models\UserFavoriteAR;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

final class ProductReadRepository
{
    public function provider(array $q, ?int $userId = null): ActiveDataProvider
    {
        $query = ProductAR::find()->with('images'); // ленивые N+1 — избегаем

        if (!empty($q['category'])) $query->andWhere(['category' => $q['category']]);
        if (!empty($q['sort']) && $q['sort'] === 'name') $query->orderBy(['name' => SORT_ASC]);

        // фильтр избранного (для авторизованного)
        if ($userId && isset($q['favorite']) && (int)$q['favorite'] === 1) {
            $query->innerJoin(UserFavoriteAR::tableName().' uf', 'uf.product_id = '.ProductAR::tableName().'.id AND uf.user_id = :u', [':u' => $userId]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => min(100, (int)($q['per_page'] ?? 20)),
                'page' => max(0, (int)($q['page'] ?? 1) - 1),
            ],
        ]);
    }

    /** Карта избранного для пачки id → true */
    public function favoritesMap(array $productIds, int $userId): array
    {
        if (!$productIds) return [];
        return UserFavoriteAR::find()->select('product_id')->where(['user_id' => $userId, 'product_id' => $productIds])
            ->indexBy('product_id')->column();
    }
}