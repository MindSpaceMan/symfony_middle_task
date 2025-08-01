<?php // modules/market/controllers/ProductsController.php
namespace Market\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use Market\repositories\ProductReadRepository;
use Market\services\ProductViewFactory;

final class ProductsController extends Controller
{
    public $enableCsrfValidation = false;

    public function __construct($id, $module,
                                private ProductReadRepository $repo,
                                private ProductViewFactory $factory,
        $config = []
    ) { parent::__construct($id, $module, $config); }

    public function actionIndex(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->isGuest ? null : (int)Yii::$app->user->id;

        $provider = $this->repo->provider(Yii::$app->request->get(), $userId);
        $rows = $provider->getModels();
        $ids = array_map(fn($p) => (int)$p->id, $rows);

        $favMap = $userId ? $this->repo->favoritesMap($ids, $userId) : null;

        $data = array_map(fn($p) => $this->factory->create($p, $favMap), $rows);

        return $this->asJson([
            'page' => $provider->pagination->page + 1,
            'per_page' => $provider->pagination->pageSize,
            'total' => $provider->totalCount,
            'items' => $data,
        ]);
    }

    public function actionView(int $id): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $p = \Market\models\ProductAR::find()->with('images')->where(['id'=>$id])->one();
        if (!$p) return $this->asJson(['error'=>'product_not_found'])->setStatusCode(404);

        $userId = Yii::$app->user->isGuest ? null : (int)Yii::$app->user->id;
        $favMap = ($userId) ? [$id => (bool)\Market\models\UserFavoriteAR::find()->where(['user_id'=>$userId,'product_id'=>$id])->exists()] : null;

        return $this->asJson($this->factory->create($p, $favMap));
    }
}