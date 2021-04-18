<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416152258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_pyp_post DROP FOREIGN KEY FK_AC3244464B89032C');
        $this->addSql('DROP INDEX idx_ac3244464b89032c ON user_pyp_post');
        $this->addSql('CREATE INDEX IDX_9AA0D94B4B89032C ON user_pyp_post (post_id)');
        $this->addSql('ALTER TABLE user_pyp_post ADD CONSTRAINT FK_AC3244464B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_pyp_post DROP FOREIGN KEY FK_9AA0D94B4B89032C');
        $this->addSql('DROP INDEX idx_9aa0d94b4b89032c ON user_pyp_post');
        $this->addSql('CREATE INDEX IDX_AC3244464B89032C ON user_pyp_post (post_id)');
        $this->addSql('ALTER TABLE user_pyp_post ADD CONSTRAINT FK_9AA0D94B4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }
}
