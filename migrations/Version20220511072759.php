<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511072759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archive (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, datedebut DATE NOT NULL, datefin DATE DEFAULT NULL, budget INT DEFAULT NULL, couts INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, projet_id INT DEFAULT NULL, corps LONGTEXT NOT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), INDEX IDX_B6BD307FC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_signalement_admin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_C9566029A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, datedebut DATE NOT NULL, datefin DATE DEFAULT NULL, budget INT DEFAULT NULL, couts INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_tache (id INT AUTO_INCREMENT NOT NULL, tache_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, datedebut DATE DEFAULT NULL, datefin DATE DEFAULT NULL, INDEX IDX_EC632090D2235D39 (tache_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, datedebut DATE NOT NULL, datefin DATE DEFAULT NULL, statut VARCHAR(255) NOT NULL, validation TINYINT(1) DEFAULT NULL, INDEX IDX_93872075C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, projetgestionnaire_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, settingtheme VARCHAR(255) NOT NULL, settinginterfacetype VARCHAR(255) NOT NULL, confirmationlecturemessage TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D64982D27837 (projetgestionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_projet (user_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_35478794A76ED395 (user_id), INDEX IDX_35478794C18272 (projet_id), PRIMARY KEY(user_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tache (user_id INT NOT NULL, tache_id INT NOT NULL, INDEX IDX_7145DB2DA76ED395 (user_id), INDEX IDX_7145DB2DD2235D39 (tache_id), PRIMARY KEY(user_id, tache_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE message_signalement_admin ADD CONSTRAINT FK_C9566029A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE sous_tache ADD CONSTRAINT FK_EC632090D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64982D27837 FOREIGN KEY (projetgestionnaire_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE user_projet ADD CONSTRAINT FK_35478794A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_projet ADD CONSTRAINT FK_35478794C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tache ADD CONSTRAINT FK_7145DB2DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tache ADD CONSTRAINT FK_7145DB2DD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC18272');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075C18272');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64982D27837');
        $this->addSql('ALTER TABLE user_projet DROP FOREIGN KEY FK_35478794C18272');
        $this->addSql('ALTER TABLE sous_tache DROP FOREIGN KEY FK_EC632090D2235D39');
        $this->addSql('ALTER TABLE user_tache DROP FOREIGN KEY FK_7145DB2DD2235D39');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message_signalement_admin DROP FOREIGN KEY FK_C9566029A76ED395');
        $this->addSql('ALTER TABLE user_projet DROP FOREIGN KEY FK_35478794A76ED395');
        $this->addSql('ALTER TABLE user_tache DROP FOREIGN KEY FK_7145DB2DA76ED395');
        $this->addSql('DROP TABLE archive');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_signalement_admin');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE sous_tache');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_projet');
        $this->addSql('DROP TABLE user_tache');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
