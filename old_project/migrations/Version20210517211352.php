<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517211352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_influence CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_influence ADD CONSTRAINT FK_489389D5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_489389D5A76ED395 ON user_influence (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_influence DROP FOREIGN KEY FK_489389D5A76ED395');
        $this->addSql('DROP INDEX UNIQ_489389D5A76ED395 ON user_influence');
        $this->addSql('ALTER TABLE user_influence CHANGE user_id user_id INT NOT NULL');
    }
}
