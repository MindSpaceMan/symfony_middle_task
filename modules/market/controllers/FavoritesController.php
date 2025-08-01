<?php // modules/market/controllers/FavoritesController.php
namespace Market\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use Market\repositories\FavoriteRepository;
use Market\repositories\ProductReadRepository;
use Market\services\ProductViewFactory;

final class FavoritesController extends Controller
{
    public $enableCsrfValidation = false;
    public function __construct($id, $module,
                                private FavoriteRepository $fav,
                                private ProductReadRepository $repo,
                                private ProductViewFactory $factory,
        $config = []
    ) { parent::__construct($id, $module, $config); }

    public function actionAdd(int $productId): Response
    {
        if (Yii::$app->user->isGuest) return $this->asJson(['error'=>'unauthorized'])->setStatusCode(401);
        $this->fav->add((int)Yii::$app->user->id, $productId);
        return $this->asJson(['ok'=>true]);
    }
    public function actionRemove(int $productId): Response
    {
        if (Yii::$app->user->isGuest) return $this->asJson(['error'=>'unauthorized'])->setStatusCode(401);
        $this->fav->remove((int)Yii::$app->user->id, $productId);
        return $this->asJson(['ok'=>true]);
    }
    /** Список избранного c теми же фильтрами/сортировкой */
    public function actionIndex(): Response
    {
        if (Yii::$app->user->isGuest) return $this->asJson(['error'=>'unauthorized'])->setStatusCode(401);

        $q = Yii::$app->request->get(); $q['favorite'] = 1;
        $provider = $this->repo->provider($q, (int)Yii::$app->user->id);
        $rows = $provider->getModels();
        $ids = array_map(fn($p) => (int)$p->id, $rows);
        $favMap = $this->repo->favoritesMap($ids, (int)Yii::$app->user->id);

        $data = array_map(fn($p) => $this->factory->create($p, $favMap), $rows);
        return $this->asJson([
            'page' => $provider->pagination->page + 1,
            'per_page' => $provider->pagination->pageSize,
            'total' => $provider->totalCount,
            'items' => $data,
        ]);
    }
}