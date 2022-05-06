<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506132837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD user_id INT DEFAULT NULL, ADD projet_id INT DEFAULT NULL, CHANGE corp corps LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA76ED395 ON message (user_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FC18272 ON message (projet_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BD77FE5F');
        $this->addSql('DROP INDEX IDX_8D93D649BD77FE5F ON user');
        $this->addSql('ALTER TABLE user ADD confirmationlecturemessage TINYINT(1) DEFAULT NULL, DROP confirmationlecturemessage_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC18272');
        $this->addSql('DROP INDEX IDX_B6BD307FA76ED395 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FC18272 ON message');
        $this->addSql('ALTER TABLE message DROP user_id, DROP projet_id, CHANGE corps corp LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD confirmationlecturemessage_id INT DEFAULT NULL, DROP confirmationlecturemessage');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649BD77FE5F FOREIGN KEY (confirmationlecturemessage_id) REFERENCES message (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649BD77FE5F ON `user` (confirmationlecturemessage_id)');
    }
}
