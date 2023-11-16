<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014140250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access_token (id UUID NOT NULL, client_id UUID DEFAULT NULL, identifier TEXT NOT NULL, user_identifier VARCHAR(255) NOT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, scopes TEXT NOT NULL, is_revoked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6A2DD6819EB6921 ON access_token (client_id)');
        $this->addSql('COMMENT ON COLUMN access_token.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN access_token.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN access_token.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE auth_code (id UUID NOT NULL, client_id UUID DEFAULT NULL, identifier TEXT NOT NULL, user_identifier VARCHAR(255) NOT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, redirect_uri VARCHAR(500) NOT NULL, scopes TEXT NOT NULL, is_revoked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5933D02C19EB6921 ON auth_code (client_id)');
        $this->addSql('COMMENT ON COLUMN auth_code.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN auth_code.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN auth_code.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE client (id UUID NOT NULL, identifier VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, redirect_uri VARCHAR(500) NOT NULL, is_confidential BOOLEAN NOT NULL, grant_types TEXT NOT NULL, scopes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN client.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE client_secret (id UUID NOT NULL, client_id UUID DEFAULT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, secret VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_904A35619EB6921 ON client_secret (client_id)');
        $this->addSql('COMMENT ON COLUMN client_secret.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN client_secret.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN client_secret.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_token (id UUID NOT NULL, access_token_id UUID DEFAULT NULL, identifier VARCHAR(255) NOT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, scopes TEXT NOT NULL, is_revoked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F21952CCB2688 ON refresh_token (access_token_id)');
        $this->addSql('COMMENT ON COLUMN refresh_token.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN refresh_token.access_token_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN refresh_token.expiry_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_secret ADD CONSTRAINT FK_904A35619EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F21952CCB2688 FOREIGN KEY (access_token_id) REFERENCES access_token (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE access_token DROP CONSTRAINT FK_B6A2DD6819EB6921');
        $this->addSql('ALTER TABLE auth_code DROP CONSTRAINT FK_5933D02C19EB6921');
        $this->addSql('ALTER TABLE client_secret DROP CONSTRAINT FK_904A35619EB6921');
        $this->addSql('ALTER TABLE refresh_token DROP CONSTRAINT FK_C74F21952CCB2688');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE auth_code');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_secret');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE "user"');
    }
}
