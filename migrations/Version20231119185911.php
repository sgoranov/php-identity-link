<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119185911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token DROP CONSTRAINT fk_b6a2dd6819eb6921');
        $this->addSql('DROP INDEX idx_b6a2dd6819eb6921');
        $this->addSql('ALTER TABLE access_token ADD client_identifier VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE access_token DROP client_id');
        $this->addSql('ALTER TABLE access_token ALTER scopes DROP NOT NULL');
        $this->addSql('ALTER TABLE auth_code DROP CONSTRAINT fk_5933d02c19eb6921');
        $this->addSql('DROP INDEX idx_5933d02c19eb6921');
        $this->addSql('ALTER TABLE auth_code ADD client_identifier VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE auth_code DROP client_id');
        $this->addSql('ALTER TABLE auth_code ALTER scopes DROP NOT NULL');
        $this->addSql('ALTER TABLE client ALTER scopes DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client ALTER scopes SET NOT NULL');
        $this->addSql('ALTER TABLE access_token ADD client_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE access_token DROP client_identifier');
        $this->addSql('ALTER TABLE access_token ALTER scopes SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN access_token.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT fk_b6a2dd6819eb6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6a2dd6819eb6921 ON access_token (client_id)');
        $this->addSql('ALTER TABLE auth_code ADD client_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE auth_code DROP client_identifier');
        $this->addSql('ALTER TABLE auth_code ALTER scopes SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN auth_code.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT fk_5933d02c19eb6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5933d02c19eb6921 ON auth_code (client_id)');
    }
}
