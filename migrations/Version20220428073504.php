<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428073504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, datedebut DATE NOT NULL, datefin DATE DEFAULT NULL, statut VARCHAR(255) NOT NULL, validation TINYINT(1) NOT NULL, INDEX IDX_93872075C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tache (user_id INT NOT NULL, tache_id INT NOT NULL, INDEX IDX_7145DB2DA76ED395 (user_id), INDEX IDX_7145DB2DD2235D39 (tache_id), PRIMARY KEY(user_id, tache_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE user_tache ADD CONSTRAINT FK_7145DB2DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tache ADD CONSTRAINT FK_7145DB2DD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_tache DROP FOREIGN KEY FK_7145DB2DD2235D39');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE user_tache');
    }
}
