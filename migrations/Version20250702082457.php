<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702082457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //enum
        $this->addSql(/** @lang SQL PostgresSQL */"CREATE TYPE coupon_discount_enum AS ENUM ('fixed', 'percent')");
        //main
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE TABLE coupon (id UUID NOT NULL, code VARCHAR(255) NOT NULL, discount_type coupon_discount_enum NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE UNIQUE INDEX UNIQ_64BF3F0277153098 ON coupon (code)');
        $this->addSql(/** @lang SQL PostgresSQL */'COMMENT ON COLUMN coupon.id IS \'(DC2Type:uuid)\'');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE TABLE product (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql(/** @lang SQL PostgresSQL */'COMMENT ON COLUMN product.id IS \'(DC2Type:uuid)\'');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql(/** @lang SQL PostgresSQL */'COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql(/** @lang SQL PostgresSQL */'COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql(/** @lang SQL PostgresSQL */'COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql(/** @lang SQL PostgresSQL */'DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(/** @lang SQL PostgresSQL */'CREATE SCHEMA public');
        $this->addSql(/** @lang SQL PostgresSQL */'DROP TABLE coupon');
        $this->addSql(/** @lang SQL PostgresSQL */'DROP TABLE product');
        $this->addSql(/** @lang SQL PostgresSQL */'DROP TABLE messenger_messages');
    }
}
