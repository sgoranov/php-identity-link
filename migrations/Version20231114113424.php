<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114113424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token ALTER scopes DROP NOT NULL');
        $this->addSql('ALTER TABLE auth_code ALTER scopes DROP NOT NULL');
        $this->addSql('ALTER TABLE client ALTER scopes DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client ALTER scopes SET NOT NULL');
        $this->addSql('ALTER TABLE access_token ALTER scopes SET NOT NULL');
        $this->addSql('ALTER TABLE auth_code ALTER scopes SET NOT NULL');
    }
}
