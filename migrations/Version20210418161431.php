<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418161431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('DROP TABLE IF EXISTS user_pyp_post');
        $this->addSql('DROP TABLE IF EXISTS user_pipe_post');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_pyp_post (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9AA0D94B4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_pyp_post ADD CONSTRAINT FK_9AA0D94B4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE IF EXISTS user_pipe_post');
        $this->addSql('DROP TABLE IF EXISTS user_pyp_post');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_pipe_post (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9AA0D94B4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_pipe_post ADD CONSTRAINT FK_AC3244464B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }
}
