<?php // console/migrations/m240801_100001_catalog_media_and_favorites.php
use yii\db\Migration;

final class m240801_100001_catalog_media_and_favorites extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%product_images}}', [
            'id'         => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'source'     => $this->string(10)->notNull(),       // local|s3
            'path'       => $this->string(255)->notNull(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('idx_product_images_product','{{%product_images}}','product_id');

        $this->createTable('{{%user_favorites}}', [
            'user_id'    => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'added_at'   => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'PRIMARY KEY(user_id, product_id)',
        ]);
        $this->createIndex('idx_fav_user','{{%user_favorites}}','user_id');
        $this->createIndex('idx_fav_product','{{%user_favorites}}','product_id');
    }
    public function safeDown()
    {
        $this->dropTable('{{%user_favorites}}');
        $this->dropTable('{{%product_images}}');
    }
}