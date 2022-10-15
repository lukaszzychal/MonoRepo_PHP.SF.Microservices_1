<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014150349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA user_store');
        $this->addSql('CREATE TABLE user_store.address (uuid VARCHAR(255) NOT NULL, street VARCHAR(100) NOT NULL, house_number VARCHAR(100) NOT NULL, apartment_number VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(255) NOT NULL, postal_code_value VARCHAR(50) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE user_store.users (uuid VARCHAR(255) NOT NULL, address_id VARCHAR(255) DEFAULT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, birth_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, avatar VARCHAR(100) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_1D8E401F5B7AF75 ON user_store.users (address_id)');
        $this->addSql('ALTER TABLE user_store.users ADD CONSTRAINT FK_1D8E401F5B7AF75 FOREIGN KEY (address_id) REFERENCES user_store.address (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_store.users DROP CONSTRAINT FK_1D8E401F5B7AF75');
        $this->addSql('DROP TABLE user_store.address');
        $this->addSql('DROP TABLE user_store.users');
    }
}
