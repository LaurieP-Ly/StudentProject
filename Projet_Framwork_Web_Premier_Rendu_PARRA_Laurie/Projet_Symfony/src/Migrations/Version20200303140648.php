<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303140648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE horaires RENAME INDEX idx_39b7118f8cebaca0 TO IDX_39B7118F76E50838');
        $this->addSql('ALTER TABLE horaires RENAME INDEX idx_39b7118f2e149425 TO IDX_39B7118FD41A30BD');
        $this->addSql('ALTER TABLE clients ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients DROP roles');
        $this->addSql('ALTER TABLE horaires RENAME INDEX idx_39b7118f76e50838 TO IDX_39B7118F8CEBACA0');
        $this->addSql('ALTER TABLE horaires RENAME INDEX idx_39b7118fd41a30bd TO IDX_39B7118F2E149425');
    }
}
