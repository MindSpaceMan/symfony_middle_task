<?php
declare(strict_types=1);

namespace tests\unit;

use Market\repositories\FavoriteRepository;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\console\Application;
use yii\db\Connection;
use yii\db\Query;

/**
 * Тестируем идемпотентность add/remove на реальном SQLite in-memory.
 * Репозиторий — DB-вариант на yii\db\Connection (createCommand → insert/delete).
 */
final class FavoriteRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Поднимаем минимальное Yii-приложение с БД
        new Application([
            'id' => 'test',
            'basePath' => __DIR__,
            'components' => [
                'db' => [
                    'class' => Connection::class,
                    'dsn' => 'sqlite::memory:',
                ],
            ],
        ]);

        Yii::$app->db->open();
        Yii::$app->db->createCommand('
            CREATE TABLE user_favorite (
                user_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                added_at TEXT DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(user_id, product_id)
            )
        ')->execute();
    }

    public function testAddIsIdempotent(): void
    {
        $repo = new FavoriteRepository(Yii::$app->db);

        $repo->add(1, 100);
        $repo->add(1, 100); // повтор — не должен создать дубль

        $count = (new Query())->from('user_favorite')->count('*', Yii::$app->db);
        $this->assertSame(1, (int)$count);
    }

    public function testRemoveIsIdempotent(): void
    {
        $repo = new FavoriteRepository(Yii::$app->db);

        // Удаление отсутствующей записи — без ошибок
        $repo->remove(2, 200);

        // Добавим, затем удалим дважды
        $repo->add(2, 200);
        $repo->remove(2, 200);
        $repo->remove(2, 200);

        $count = (new Query())->from('user_favorite')->count('*', Yii::$app->db);
        $this->assertSame(0, (int)$count);
    }

    protected function tearDown(): void
    {
        Yii::$app = null;
        parent::tearDown();
    }
}