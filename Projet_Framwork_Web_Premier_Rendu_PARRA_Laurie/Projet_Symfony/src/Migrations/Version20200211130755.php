<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211130755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE horaires (id INT AUTO_INCREMENT NOT NULL, id_salle_id INT NOT NULL, id_cours_id INT NOT NULL, jour VARCHAR(20) NOT NULL, heure VARCHAR(20) NOT NULL, INDEX IDX_39B7118F8CEBACA0 (id_salle_id), INDEX IDX_39B7118F2E149425 (id_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horaires ADD CONSTRAINT FK_39B7118F8CEBACA0 FOREIGN KEY (id_salle_id) REFERENCES salles (id)');
        $this->addSql('ALTER TABLE horaires ADD CONSTRAINT FK_39B7118F2E149425 FOREIGN KEY (id_cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE horaires');
    }
}
