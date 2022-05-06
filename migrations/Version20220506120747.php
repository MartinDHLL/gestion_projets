<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506120747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, corp LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD confirmationlecturemessage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BD77FE5F FOREIGN KEY (confirmationlecturemessage_id) REFERENCES message (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BD77FE5F ON user (confirmationlecturemessage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649BD77FE5F');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP INDEX IDX_8D93D649BD77FE5F ON `user`');
        $this->addSql('ALTER TABLE `user` DROP confirmationlecturemessage_id');
    }
}
