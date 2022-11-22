<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221121222229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA notification');
        $this->addSql('CREATE TABLE notification.event_stream (id UUID NOT NULL, event_number INT NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN notification.event_stream.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.event_stream.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification.events (stream_id UUID NOT NULL, version INT NOT NULL, place_occurrence VARCHAR(255) NOT NULL, event_name VARCHAR(255) NOT NULL, event JSONB NOT NULL, PRIMARY KEY(stream_id))');
        $this->addSql('COMMENT ON COLUMN notification.events.stream_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('CREATE TABLE domain_event_failed (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_351DA2A8FB7336F0 ON domain_event_failed (queue_name)');
        $this->addSql('CREATE INDEX IDX_351DA2A8E3BD61CE ON domain_event_failed (available_at)');
        $this->addSql('CREATE INDEX IDX_351DA2A816BA31DB ON domain_event_failed (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_domain_event_failed() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'domain_event_failed\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON domain_event_failed;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON domain_event_failed FOR EACH ROW EXECUTE PROCEDURE notify_domain_event_failed();');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE notification.event_stream');
        $this->addSql('DROP TABLE notification.events');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE domain_event_failed');
    }
}
