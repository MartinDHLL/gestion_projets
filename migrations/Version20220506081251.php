<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506081251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sous_tache ADD tache_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_tache ADD CONSTRAINT FK_EC632090D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('CREATE INDEX IDX_EC632090D2235D39 ON sous_tache (tache_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sous_tache DROP FOREIGN KEY FK_EC632090D2235D39');
        $this->addSql('DROP INDEX IDX_EC632090D2235D39 ON sous_tache');
        $this->addSql('ALTER TABLE sous_tache DROP tache_id');
    }
}
